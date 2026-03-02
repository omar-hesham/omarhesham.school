<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\ConsentRecord;
use App\Models\User;
use App\Notifications\ParentConsentRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class ConsentService
{
    public function requestConsent(User $child, string $guardianEmail): ConsentRecord
    {
        $record = ConsentRecord::create([
            'user_id'        => $child->id,
            'consent_token'  => Str::random(64),
            'action'         => 'requested',
            'guardian_email' => $guardianEmail,
        ]);

        $child->profile->update([
            'guardian_email' => $guardianEmail,
            'consent_status' => 'pending',
        ]);

        // Send email to guardian
        Notification::route('mail', $guardianEmail)
            ->notify(new ParentConsentRequest($record, $child));

        AuditLog::record($child->id, 'consent_requested', 'User', $child->id);

        return $record;
    }

    public function processConsent(string $token, string $action): bool
    {
        $record = ConsentRecord::where('consent_token', $token)
            ->where('action', 'requested')
            ->first();

        if (!$record || $record->created_at->diffInDays(now()) > 7) {
            return false;
        }

        $record->update([
            'action'       => $action,
            'responded_at' => now(),
        ]);

        $record->user->profile->update([
            'consent_status' => $action === 'approved' ? 'approved' : 'denied',
        ]);

        $auditAction = $action === 'approved' ? 'consent_approved' : 'consent_denied';
        AuditLog::record($record->user_id, $auditAction, 'User', $record->user_id);

        return true;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\ConsentRecord;
use App\Models\User;
use App\Notifications\ParentConsentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendConsentReminders extends Command
{
    protected $signature   = 'consent:send-reminders';
    protected $description = 'Send reminder emails to guardians who have not responded to consent requests within 2 days';

    public function handle(): int
    {
        // Find pending records that were requested at least 2 days ago but not yet expired (< 7 days)
        $pending = ConsentRecord::where('action', 'requested')
            ->where('created_at', '<=', now()->subDays(2))
            ->where('created_at', '>=', now()->subDays(7))
            ->with('user')
            ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending consent reminders to send.');
            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($pending as $record) {
            try {
                Notification::route('mail', $record->guardian_email)
                    ->notify(new ParentConsentRequest($record, $record->user));

                $sent++;
            } catch (\Throwable $e) {
                $this->error("Failed to send reminder for record #{$record->id}: {$e->getMessage()}");
            }
        }

        $this->info("Sent {$sent} consent reminder(s).");

        return self::SUCCESS;
    }
}

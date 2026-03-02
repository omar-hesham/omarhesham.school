<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ConsentRecord;
use App\Services\ConsentService;

class ParentConsentController extends Controller
{
    public function __construct(private ConsentService $consentService) {}

    // Show info page so guardian knows what they are consenting to
    public function info(string $token)
    {
        $record = ConsentRecord::where('consent_token', $token)
            ->where('action', 'requested')
            ->firstOrFail();

        if ($record->created_at->diffInDays(now()) > 7) {
            return view('auth.consent-expired');
        }

        $child = $record->user;

        return view('auth.consent-info', compact('record', 'child'));
    }

    public function approve(string $token)
    {
        $success = $this->consentService->processConsent($token, 'approved');

        if (!$success) {
            return view('auth.consent-expired');
        }

        return view('auth.consent-result', ['action' => 'approved']);
    }

    public function deny(string $token)
    {
        $success = $this->consentService->processConsent($token, 'denied');

        if (!$success) {
            return view('auth.consent-expired');
        }

        return view('auth.consent-result', ['action' => 'denied']);
    }
}

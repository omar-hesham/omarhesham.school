<?php

namespace App\Console\Commands;

use App\Models\ConsentRecord;
use App\Models\UserProfile;
use Illuminate\Console\Command;

class CleanExpiredConsents extends Command
{
    protected $signature   = 'consent:clean-expired';
    protected $description = 'Delete consent records older than 7 days that were never responded to, and reset the child account status';

    public function handle(): int
    {
        $expired = ConsentRecord::where('action', 'requested')
            ->where('created_at', '<', now()->subDays(7))
            ->with('user.profile')
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No expired consent records found.');
            return self::SUCCESS;
        }

        $cleaned = 0;

        foreach ($expired as $record) {
            // Mark the child's profile consent as denied so they are blocked
            $record->user?->profile?->update([
                'consent_status' => 'denied',
            ]);

            $record->delete();
            $cleaned++;
        }

        $this->info("Cleaned {$cleaned} expired consent record(s).");

        return self::SUCCESS;
    }
}

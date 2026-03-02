<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class PruneAuditLogs extends Command
{
    protected $signature   = 'audit:prune {--days=365 : Delete audit logs older than this many days}';
    protected $description = 'Prune audit logs older than the specified number of days (GDPR retention)';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        if ($days < 30) {
            $this->error('Minimum retention period is 30 days.');
            return self::FAILURE;
        }

        $cutoff  = now()->subDays($days);
        $deleted = AuditLog::where('created_at', '<', $cutoff)->delete();

        $this->info("Pruned {$deleted} audit log record(s) older than {$days} days (before {$cutoff->toDateString()}).");

        return self::SUCCESS;
    }
}

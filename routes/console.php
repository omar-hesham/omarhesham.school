<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Schedule
|--------------------------------------------------------------------------
| Laravel 11 defines the scheduler here (replaces app/Console/Kernel.php).
| Forge cron: * * * * * forge php /home/forge/omarhesham.school/current/artisan schedule:run
*/

// Send reminder emails to guardians who have not responded within 2 days
Schedule::command('consent:send-reminders')->daily();

// Delete expired (>7 day) consent tokens and block the child account
Schedule::command('consent:clean-expired')->daily();

// Prune audit logs older than 1 year (GDPR Article 5 retention limit)
Schedule::command('audit:prune --days=365')->monthly();

// Email weekly progress summaries to each teacher (Saturday 8 am Cairo time)
Schedule::command('reports:weekly-teacher')->weeklyOn(6, '08:00');

<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\MinorProtection;
use App\Http\Middleware\SecureHeaders;
use App\Http\Middleware\VerifiedBan;
use App\Events\BadgeEarned;
use App\Listeners\SendBadgeEarnedNotification;
use App\Console\Commands\SendWeeklyReports;
use App\Console\Commands\SendConsentReminders;
use App\Console\Commands\CleanExpiredConsents;
use App\Console\Commands\PruneAuditLogs;
use App\Providers\ViewServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global web middleware — secure headers on every response
        $middleware->web(append: [
            SecureHeaders::class,
        ]);

        // Named middleware aliases
        $middleware->alias([
            'role'             => RoleMiddleware::class,
            'minor.protection' => MinorProtection::class,
            'verified.ban'     => VerifiedBan::class,
        ]);

        // Trust all proxies (Cloudflare + Forge)
        $middleware->trustProxies(at: '*');
    })
    ->withEvents(fn() => [
        BadgeEarned::class => [
            SendBadgeEarnedNotification::class,
        ],
    ])
    ->withProviders([
        ViewServiceProvider::class,
    ])
    ->withSchedule(function ($schedule) {
        // Weekly student + teacher reports — every Saturday 08:00 Cairo
        $schedule->command(SendWeeklyReports::class)
                 ->weeklyOn(6, '08:00')
                 ->timezone('Africa/Cairo')
                 ->withoutOverlapping()
                 ->onOneServer();

        // Consent reminders (2–7 days old) — daily 09:00 Cairo
        $schedule->command(SendConsentReminders::class)
                 ->dailyAt('09:00')
                 ->timezone('Africa/Cairo')
                 ->withoutOverlapping()
                 ->onOneServer();

        // Expire consents older than 7 days — daily 02:00 Cairo
        $schedule->command(CleanExpiredConsents::class)
                 ->dailyAt('02:00')
                 ->timezone('Africa/Cairo')
                 ->withoutOverlapping()
                 ->onOneServer();

        // Prune audit logs older than 365 days — monthly
        $schedule->command(PruneAuditLogs::class, ['--days=365'])
                 ->monthly()
                 ->timezone('Africa/Cairo')
                 ->withoutOverlapping()
                 ->onOneServer();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

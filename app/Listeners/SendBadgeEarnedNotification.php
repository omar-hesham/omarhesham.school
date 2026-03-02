<?php

namespace App\Listeners;

use App\Events\BadgeEarned;
use App\Models\AuditLog;
use App\Notifications\BadgeEarnedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBadgeEarnedNotification implements ShouldQueue
{
    public string $queue = 'emails';

    public function handle(BadgeEarned $event): void
    {
        // Audit log
        AuditLog::record(
            $event->user->id,
            'badge_earned',
            'Badge',
            $event->badge->id,
            [
                'badge_key'  => $event->badge->key,
                'badge_name' => $event->badge->name,
                'xp_value'   => $event->badge->xp_value,
            ]
        );

        // Only email if the badge has XP >= 100 (significant badges only)
        // Avoids inbox flooding on rapid consecutive badge earning
        if ($event->badge->xp_value >= 100) {
            $event->user->notify(new BadgeEarnedNotification($event->badge));
        }
    }
}

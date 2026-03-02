<?php

namespace App\Notifications;

use App\Models\Badge;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadgeEarnedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Badge $badge)
    {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("{$this->badge->emoji} New Badge: {$this->badge->name} — omarhesham.school")
            ->view('emails.badges.earned', [
                'user'           => $notifiable,
                'badge'          => $this->badge,
                'dashboardUrl'   => route('student.dashboard'),
                'recipientEmail' => $notifiable->email,
            ]);
    }

    public function tries(): int { return 3; }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountBanned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly string $banReason)
    {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Suspended — omarhesham.school')
            ->view('emails.account.banned', [
                'user'           => $notifiable,
                'banReason'      => $this->banReason,
                'recipientEmail' => $notifiable->email,
                'appealEmail'    => 'appeals@omarhesham.school',
            ]);
    }

    public function tries(): int { return 3; }
}

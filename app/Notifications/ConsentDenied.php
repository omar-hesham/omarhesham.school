<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsentDenied extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct() { $this->onQueue('emails')->afterCommit(); }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account access denied — omarhesham.school')
            ->view('emails.consent.denied', [
                'user'           => $notifiable,
                'recipientEmail' => $notifiable->email,
            ]);
    }

    public function tries(): int { return 3; }
}

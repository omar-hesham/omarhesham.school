<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConsentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly string $guardianEmail)
    {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your account has been activated — omarhesham.school')
            ->view('emails.consent.approved', [
                'user'           => $notifiable,
                'dashboardUrl'   => route('student.dashboard'),
                'recipientEmail' => $notifiable->email,
            ]);
    }

    public function tries(): int { return 3; }
}

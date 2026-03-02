<?php

namespace App\Notifications;

use App\Models\ProgressLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProgressApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ProgressLog $log,
        public readonly string      $teacherName,
    ) {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Your Hifz session was approved — omarhesham.school')
            ->view('emails.progress.approved', [
                'log'            => $this->log,
                'teacherName'    => $this->teacherName,
                'user'           => $notifiable,
                'dashboardUrl'   => route('student.dashboard'),
                'recipientEmail' => $notifiable->email,
            ]);
    }

    public function tries(): int { return 3; }
}

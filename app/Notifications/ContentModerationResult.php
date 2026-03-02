<?php

namespace App\Notifications;

use App\Models\ContentItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentModerationResult extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ContentItem $item,
        public readonly string      $result,          // 'approved' | 'rejected'
        public readonly ?string     $rejectionNote = null,
    ) {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $approved = $this->result === 'approved';

        return (new MailMessage)
            ->subject(($approved ? '✅ Content Approved' : '❌ Content Rejected') . ' — omarhesham.school')
            ->view('emails.moderation.result', [
                'item'           => $this->item,
                'approved'       => $approved,
                'rejectionNote'  => $this->rejectionNote,
                'uploader'       => $notifiable,
                'uploadUrl'      => route('teacher.content.upload.form'),
                'recipientEmail' => $notifiable->email,
            ]);
    }

    public function tries(): int { return 3; }
}

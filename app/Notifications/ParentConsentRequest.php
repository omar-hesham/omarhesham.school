<?php

namespace App\Notifications;

use App\Models\ConsentRecord;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParentConsentRequest extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ConsentRecord $record,
        public readonly User          $child,
    ) {
        $this->onQueue('emails');
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $approveUrl = route('consent.approve', $this->record->consent_token);
        $denyUrl    = route('consent.deny',    $this->record->consent_token);
        $infoUrl    = route('consent.info',    $this->record->consent_token);

        return (new MailMessage)
            ->subject('Action Required: Parental Consent for ' . $this->child->name . ' — omarhesham.school')
            ->replyTo('support@omarhesham.school', 'Omar Hesham School Support')
            ->view('emails.consent.request', [
                'child'          => $this->child,
                'record'         => $this->record,
                'approveUrl'     => $approveUrl,
                'denyUrl'        => $denyUrl,
                'infoUrl'        => $infoUrl,
                'recipientEmail' => $this->record->guardian_email,
                'expiresAt'      => $this->record->created_at->addDays(7)->format('F j, Y'),
            ]);
    }

    public function retryUntil(): \DateTime
    {
        return now()->addHours(6);
    }

    public function tries(): int    { return 3; }
    public function backoff(): array { return [30, 120, 600]; }
}

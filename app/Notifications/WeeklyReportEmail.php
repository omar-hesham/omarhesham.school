<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class WeeklyReportEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param User       $student   The student this report is about.
     * @param Collection $logs      ProgressLog collection for the past 7 days.
     * @param int        $streak    Current memorization streak in days.
     * @param Collection $badges    Newly earned badges this week (may be empty).
     * @param bool       $toTeacher True when the same report is sent to the teacher.
     */
    public function __construct(
        public readonly User       $student,
        public readonly Collection $logs,
        public readonly int        $streak,
        public readonly Collection $badges,
        public readonly bool       $toTeacher = false,
    ) {
        $this->onQueue('emails')->afterCommit();
    }

    public function via(object $notifiable): array { return ['mail']; }

    public function toMail(object $notifiable): MailMessage
    {
        $weekRange = now()->subDays(6)->format('M j') . ' – ' . now()->format('M j, Y');

        $totalAyahs  = $this->logs->sum(fn($l) => $l->ayah_to - $l->ayah_from + 1);
        $avgQuality  = $this->logs->avg('quality_score') ?? 0;
        $daysLogged  = $this->logs->groupBy('logged_at')->count();
        $approvedCnt = $this->logs->whereNotNull('approved_by')->count();

        $dailyBreakdown = collect(range(6, 0))->mapWithKeys(function ($daysAgo) {
            $date  = now()->subDays($daysAgo)->toDateString();
            $label = now()->subDays($daysAgo)->format('D, M j');
            $count = $this->logs
                ->filter(fn($l) => (string) $l->logged_at === $date)
                ->sum(fn($l) => $l->ayah_to - $l->ayah_from + 1);
            return [$label => $count];
        });

        $maxAyahs = max($dailyBreakdown->max() ?: 1, 1);

        return (new MailMessage)
            ->subject("Weekly Hifz Report — {$this->student->name} ({$weekRange})")
            ->view('emails.reports.weekly', [
                'student'        => $this->student,
                'logs'           => $this->logs,
                'streak'         => $this->streak,
                'badges'         => $this->badges,
                'toTeacher'      => $this->toTeacher,
                'weekRange'      => $weekRange,
                'totalAyahs'     => $totalAyahs,
                'avgQuality'     => round($avgQuality, 1),
                'daysLogged'     => $daysLogged,
                'approvedCnt'    => $approvedCnt,
                'dailyBreakdown' => $dailyBreakdown,
                'maxAyahs'       => $maxAyahs,
                'recipientEmail' => $notifiable->email,
                'dashboardUrl'   => route($this->toTeacher ? 'teacher.dashboard' : 'student.dashboard'),
                'reportUrl'      => route('student.progress.report') . '?format=pdf',
            ]);
    }

    public function tries(): int    { return 3; }
    public function backoff(): array { return [60, 300]; }
}

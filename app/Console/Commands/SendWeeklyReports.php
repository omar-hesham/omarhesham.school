<?php

namespace App\Console\Commands;

use App\Models\ProgressLog;
use App\Models\User;
use App\Notifications\WeeklyReportEmail;
use App\Services\BadgeService;
use Illuminate\Console\Command;

class SendWeeklyReports extends Command
{
    protected $signature   = 'reports:weekly';
    protected $description = 'Send weekly Hifz progress reports to students and their assigned teachers';

    public function __construct(private readonly BadgeService $badges)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $from = now()->subDays(6)->toDateString();
        $to   = now()->toDateString();

        // Cursor = memory-safe for large student bases
        $students = User::where('role', 'student')
            ->where('is_banned', false)
            ->with(['profile', 'teacherLinks.teacher'])
            ->whereHas('progressLogs', fn ($q) => $q->whereBetween('logged_at', [$from, $to]))
            ->cursor();

        $sent = 0;

        foreach ($students as $student) {
            $logs = ProgressLog::where('user_id', $student->id)
                ->whereBetween('logged_at', [$from, $to])
                ->orderBy('logged_at')
                ->get();

            if ($logs->isEmpty()) {
                continue;
            }

            $streak = $this->calculateStreak($student->id);
            $badges = $this->badges->getNewBadgesThisWeek($student->id);

            // 1. Notify student
            $student->notify(new WeeklyReportEmail(
                student:   $student,
                logs:      $logs,
                streak:    $streak,
                badges:    $badges,
                toTeacher: false,
            ));

            // 2. Notify assigned teacher (if any, not banned)
            $teacher = $student->teacherLinks()
                ->where('is_active', true)
                ->with('teacher')
                ->first()?->teacher;

            if ($teacher && ! $teacher->is_banned) {
                $teacher->notify(new WeeklyReportEmail(
                    student:   $student,
                    logs:      $logs,
                    streak:    $streak,
                    badges:    $badges,
                    toTeacher: true,
                ));
            }

            $sent++;
        }

        $this->info("Weekly reports dispatched for {$sent} student(s).");

        return Command::SUCCESS;
    }

    private function calculateStreak(int $userId): int
    {
        $streak = 0;
        while (
            ProgressLog::where('user_id', $userId)
                ->where('logged_at', now()->subDays($streak)->toDateString())
                ->exists()
        ) {
            $streak++;
        }

        return $streak;
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\Enrollment;
use App\Services\BadgeService;

class DashboardController extends Controller
{
    public function __construct(private BadgeService $badgeService) {}

    public function index()
    {
        $user = auth()->user();

        // Current streak (consecutive days with at least one log)
        $streak = $this->calculateStreak($user->id);

        // Logs in the last 7 days
        $weeklyLogs = ProgressLog::where('user_id', $user->id)
            ->where('logged_at', '>=', now()->subDays(7)->toDateString())
            ->count();

        // Enrollments count
        $enrollments = Enrollment::where('user_id', $user->id)->count();

        // Badges earned
        $badges = $this->badgeService->getBadgesForUser($user->id);

        // Recent 5 logs
        $recentLogs = ProgressLog::where('user_id', $user->id)
            ->latest('logged_at')
            ->take(5)
            ->get();

        // Assigned teacher (if any)
        $teacher = $user->teacherLinks()
            ->where('is_active', true)
            ->with('teacher')
            ->first()?->teacher;

        return view('student.dashboard', compact(
            'user', 'streak', 'weeklyLogs', 'enrollments', 'badges', 'recentLogs', 'teacher'
        ));
    }

    protected function calculateStreak(int $userId): int
    {
        $streak = 0;
        $date   = now()->toDateString();

        while (true) {
            $hasLog = ProgressLog::where('user_id', $userId)
                ->where('logged_at', $date)
                ->exists();

            if (!$hasLog) break;

            $streak++;
            $date = now()->subDays($streak)->toDateString();
        }

        return $streak;
    }
}

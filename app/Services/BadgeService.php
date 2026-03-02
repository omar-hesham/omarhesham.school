<?php

namespace App\Services;

use App\Events\BadgeEarned;
use App\Models\Badge;
use App\Models\Enrollment;
use App\Models\ProgressLog;
use App\Models\TeacherStudentLink;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BadgeService
{
    // Cache TTL for stats that are expensive to re-compute
    private const CACHE_TTL = 300; // 5 minutes

    // ════════════════════════════════════════════════════════════════════════
    //  PUBLIC API
    // ════════════════════════════════════════════════════════════════════════

    /**
     * Run all badge checks for a user and return newly awarded badges.
     * Call this after every progress log save.
     */
    public function checkAndAward(int $userId): Collection
    {
        $user  = User::with(['profile', 'progressLogs', 'enrollments'])->findOrFail($userId);
        $stats = $this->buildStats($user);

        $newBadges = collect();

        foreach ($this->allChecks() as $check) {
            $result = $check($user, $stats);
            if ($result instanceof Collection) {
                $newBadges = $newBadges->merge($result);
            }
        }

        // Bust the user's badge cache
        Cache::forget("user_badges_{$userId}");
        Cache::forget("user_xp_{$userId}");

        return $newBadges;
    }

    /**
     * Return all badges earned by a user (cached).
     */
    public function getBadgesForUser(int $userId): Collection
    {
        return Cache::remember(
            "user_badges_{$userId}",
            self::CACHE_TTL,
            fn() => UserBadge::where('user_id', $userId)
                ->with('badge')
                ->orderByDesc('first_earned_at')
                ->get()
                ->map(fn($ub) => $ub->badge)
        );
    }

    /**
     * Badges earned for the first time in the past 7 days.
     */
    public function getNewBadgesThisWeek(int $userId): Collection
    {
        return UserBadge::where('user_id', $userId)
            ->where('first_earned_at', '>=', now()->subDays(7))
            ->with('badge')
            ->get()
            ->map(fn($ub) => $ub->badge);
    }

    /**
     * Total XP earned by a user (cached).
     */
    public function getXpForUser(int $userId): int
    {
        return Cache::remember(
            "user_xp_{$userId}",
            self::CACHE_TTL,
            fn() => UserBadge::where('user_id', $userId)
                ->join('badges', 'badges.id', '=', 'user_badges.badge_id')
                ->sum(DB::raw('badges.xp_value * user_badges.times_earned'))
        );
    }

    /**
     * Leaderboard — top N users by total XP.
     */
    public function leaderboard(int $limit = 10): Collection
    {
        return User::where('role', 'student')
            ->where('is_banned', false)
            ->withCount(['userBadges as total_xp' => fn($q) =>
                $q->join('badges', 'badges.id', '=', 'user_badges.badge_id')
                  ->select(DB::raw('SUM(badges.xp_value * user_badges.times_earned)'))
            ])
            ->orderByDesc('total_xp')
            ->limit($limit)
            ->get();
    }

    // ════════════════════════════════════════════════════════════════════════
    //  STATS BUILDER
    // ════════════════════════════════════════════════════════════════════════

    private function buildStats(User $user): array
    {
        $logs = ProgressLog::where('user_id', $user->id)->get();

        // Total ayahs ever logged
        $totalAyahs = $logs->sum(fn($l) => $l->ayah_to - $l->ayah_from + 1);

        // Total sessions
        $totalSessions = $logs->count();

        // Approved sessions count
        $approvedSessions = $logs->whereNotNull('approved_by')->count();

        // Perfect quality (5★) sessions
        $perfectSessions = $logs->where('quality_score', 5)->count();

        // High quality (4★ or 5★) sessions
        $highQualitySessions = $logs->whereIn('quality_score', [4, 5])->count();

        // Average quality across all logs
        $avgQuality = $logs->isNotEmpty()
            ? round($logs->avg('quality_score'), 2)
            : 0.0;

        // Current streak (consecutive days ending today or yesterday)
        $streak = $this->computeStreak($user->id, $logs);

        // Longest streak ever
        $longestStreak = $this->computeLongestStreak($logs);

        // Unique surahs memorised (appeared in logs)
        $uniqueSurahs = $logs->pluck('surah_number')->unique()->count();

        // Unique days logged
        $uniqueDays = $logs->pluck('logged_at')->unique()->count();

        // Sessions this week
        $sessionsThisWeek = $logs->filter(
            fn($l) => now()->subDays(7)->toDateString() <= $l->logged_at
        )->count();

        // Sessions today
        $sessionsToday = $logs->where('logged_at', today()->toDateString())->count();

        // Weekly consistency: logged at least once on 7 consecutive weeks
        $weeklyStreak = $this->computeWeeklyStreak($user->id);

        // Enrollments
        $enrollmentCount = Enrollment::where('user_id', $user->id)->count();

        // Has a teacher assigned
        $hasTeacher = TeacherStudentLink::where('student_id', $user->id)
            ->where('is_active', true)
            ->exists();

        // Is a child
        $isChild = $user->profile?->age_group === 'child';

        // Days since account creation
        $accountAgeDays = $user->created_at->diffInDays(now());

        return compact(
            'totalAyahs', 'totalSessions', 'approvedSessions',
            'perfectSessions', 'highQualitySessions', 'avgQuality',
            'streak', 'longestStreak', 'uniqueSurahs', 'uniqueDays',
            'sessionsThisWeek', 'sessionsToday', 'weeklyStreak',
            'enrollmentCount', 'hasTeacher', 'isChild', 'accountAgeDays'
        );
    }

    // ════════════════════════════════════════════════════════════════════════
    //  ALL BADGE CHECKS  (returns array of callables)
    // ════════════════════════════════════════════════════════════════════════

    private function allChecks(): array
    {
        return [
            // ── STREAK badges ─────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'first_log',
                fn($s) => $s['totalSessions'] >= 1
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_3',
                fn($s) => $s['streak'] >= 3
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_7',
                fn($s) => $s['streak'] >= 7
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_14',
                fn($s) => $s['streak'] >= 14
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_30',
                fn($s) => $s['streak'] >= 30
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_60',
                fn($s) => $s['streak'] >= 60
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'streak_100',
                fn($s) => $s['streak'] >= 100
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'longest_streak_7',
                fn($s) => $s['longestStreak'] >= 7
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'longest_streak_30',
                fn($s) => $s['longestStreak'] >= 30
            ),

            // ── VOLUME badges ─────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_10',
                fn($s) => $s['totalAyahs'] >= 10
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_50',
                fn($s) => $s['totalAyahs'] >= 50
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_100',
                fn($s) => $s['totalAyahs'] >= 100
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_500',
                fn($s) => $s['totalAyahs'] >= 500
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_1000',
                fn($s) => $s['totalAyahs'] >= 1000
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ayahs_6236',
                fn($s) => $s['totalAyahs'] >= 6236
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'sessions_10',
                fn($s) => $s['totalSessions'] >= 10
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'sessions_50',
                fn($s) => $s['totalSessions'] >= 50
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'sessions_100',
                fn($s) => $s['totalSessions'] >= 100
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'sessions_365',
                fn($s) => $s['totalSessions'] >= 365
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'surahs_3',
                fn($s) => $s['uniqueSurahs'] >= 3
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'surahs_10',
                fn($s) => $s['uniqueSurahs'] >= 10
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'surahs_30',
                fn($s) => $s['uniqueSurahs'] >= 30
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'surahs_114',
                fn($s) => $s['uniqueSurahs'] >= 114
            ),

            // ── QUALITY badges ────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'first_perfect',
                fn($s) => $s['perfectSessions'] >= 1
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'perfect_5',
                fn($s) => $s['perfectSessions'] >= 5
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'perfect_20',
                fn($s) => $s['perfectSessions'] >= 20
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'perfect_50',
                fn($s) => $s['perfectSessions'] >= 50
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'high_quality_10',
                fn($s) => $s['highQualitySessions'] >= 10
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'avg_quality_4',
                fn($s) => $s['totalSessions'] >= 10 && $s['avgQuality'] >= 4.0
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'avg_quality_45',
                fn($s) => $s['totalSessions'] >= 20 && $s['avgQuality'] >= 4.5
            ),

            // ── CONSISTENCY badges ────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'week_complete',
                fn($s) => $s['sessionsThisWeek'] >= 7
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'weekly_streak_4',
                fn($s) => $s['weeklyStreak'] >= 4
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'weekly_streak_12',
                fn($s) => $s['weeklyStreak'] >= 12
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'double_session',
                fn($s) => $s['sessionsToday'] >= 2
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'early_bird',
                fn($s) => $this->loggedBeforeHour($u->id, 7)
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'night_owl',
                fn($s) => $this->loggedAfterHour($u->id, 22)
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'unique_days_30',
                fn($s) => $s['uniqueDays'] >= 30
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'unique_days_100',
                fn($s) => $s['uniqueDays'] >= 100
            ),

            // ── SOCIAL badges ─────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'first_approval',
                fn($s) => $s['approvedSessions'] >= 1
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'approved_10',
                fn($s) => $s['approvedSessions'] >= 10
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'approved_50',
                fn($s) => $s['approvedSessions'] >= 50
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'has_teacher',
                fn($s) => $s['hasTeacher']
            ),

            // ── PROGRAM badges ────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'first_enroll',
                fn($s) => $s['enrollmentCount'] >= 1
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'multi_program',
                fn($s) => $s['enrollmentCount'] >= 3
            ),

            // ── SPECIAL badges ────────────────────────────────────────────
            fn($u, $s) => $this->checkSingle($u, $s, 'welcome',
                fn($s) => true
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'ramadan_warrior',
                fn($s) => $this->isRamadan() && $s['streak'] >= 3
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'young_hafiz',
                fn($s) => $s['isChild'] && $s['totalAyahs'] >= 50
            ),
            fn($u, $s) => $this->checkSingle($u, $s, 'faithful_year',
                fn($s) => $s['accountAgeDays'] >= 365 && $s['totalSessions'] >= 100
            ),
        ];
    }

    // ════════════════════════════════════════════════════════════════════════
    //  CORE AWARD ENGINE
    // ════════════════════════════════════════════════════════════════════════

    private function checkSingle(
        User     $user,
        array    $stats,
        string   $badgeKey,
        callable $condition
    ): Collection {
        if (!$condition($stats)) {
            return collect();
        }

        $badge = Badge::where('key', $badgeKey)->where('is_active', true)->first();
        if (!$badge) {
            return collect();
        }

        $existing = UserBadge::where('user_id', $user->id)
            ->where('badge_id', $badge->id)
            ->first();

        if ($existing && !$badge->is_repeatable) {
            return collect();
        }

        return DB::transaction(function () use ($user, $badge, $existing, $stats) {
            if ($existing && $badge->is_repeatable) {
                $existing->update([
                    'times_earned'   => $existing->times_earned + 1,
                    'last_earned_at' => now(),
                    'context'        => $this->buildContext($stats),
                ]);
            } else {
                UserBadge::create([
                    'user_id'         => $user->id,
                    'badge_id'        => $badge->id,
                    'times_earned'    => 1,
                    'context'         => $this->buildContext($stats),
                    'first_earned_at' => now(),
                    'last_earned_at'  => now(),
                ]);
            }

            event(new BadgeEarned($user, $badge));

            return collect([$badge]);
        });
    }

    // ════════════════════════════════════════════════════════════════════════
    //  STREAK CALCULATORS
    // ════════════════════════════════════════════════════════════════════════

    private function computeStreak(int $userId, Collection $logs): int
    {
        $loggedDates = $logs
            ->pluck('logged_at')
            ->map(fn($d) => (string) $d)
            ->unique()
            ->sort()
            ->values();

        if ($loggedDates->isEmpty()) return 0;

        $streak = 0;
        $today  = now()->toDateString();
        $check  = $loggedDates->last() === $today ? $today : now()->subDay()->toDateString();

        while ($loggedDates->contains($check)) {
            $streak++;
            $check = now()->subDays($streak)->toDateString();
        }

        return $streak;
    }

    private function computeLongestStreak(Collection $logs): int
    {
        $dates = $logs
            ->pluck('logged_at')
            ->map(fn($d) => (string) $d)
            ->unique()
            ->sort()
            ->values();

        if ($dates->isEmpty()) return 0;

        $longest = 1;
        $current = 1;

        for ($i = 1; $i < $dates->count(); $i++) {
            $prev = \Carbon\Carbon::parse($dates[$i - 1]);
            $curr = \Carbon\Carbon::parse($dates[$i]);

            if ($prev->addDay()->isSameDay($curr)) {
                $current++;
                $longest = max($longest, $current);
            } else {
                $current = 1;
            }
        }

        return $longest;
    }

    private function computeWeeklyStreak(int $userId): int
    {
        $weeklyActivity = ProgressLog::where('user_id', $userId)
            ->selectRaw('YEARWEEK(logged_at, 1) as yw')
            ->distinct()
            ->orderByDesc('yw')
            ->pluck('yw');

        if ($weeklyActivity->isEmpty()) return 0;

        $streak = 0;

        foreach ($weeklyActivity as $yw) {
            $expected = (int) now()->subWeeks($streak)->format('oW');
            if ((int) $yw === $expected) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    // ════════════════════════════════════════════════════════════════════════
    //  SPECIAL CONDITION HELPERS
    // ════════════════════════════════════════════════════════════════════════

    private function loggedBeforeHour(int $userId, int $hour): bool
    {
        return ProgressLog::where('user_id', $userId)
            ->whereRaw('HOUR(created_at) < ?', [$hour])
            ->exists();
    }

    private function loggedAfterHour(int $userId, int $hour): bool
    {
        return ProgressLog::where('user_id', $userId)
            ->whereRaw('HOUR(created_at) >= ?', [$hour])
            ->exists();
    }

    private function isRamadan(): bool
    {
        return Cache::remember('is_ramadan_' . today()->toDateString(), 86400, function () {
            $year  = (int) now()->format('Y');

            $ramadanPeriods = [
                2024 => ['03-11', '04-09'],
                2025 => ['03-01', '03-30'],
                2026 => ['02-18', '03-19'],
                2027 => ['02-08', '03-09'],
                2028 => ['01-28', '02-26'],
                2029 => ['01-17', '02-15'],
                2030 => ['01-06', '02-04'],
            ];

            if (!isset($ramadanPeriods[$year])) return false;

            [$start, $end] = $ramadanPeriods[$year];
            $today = now()->toDateString();
            return $today >= "{$year}-{$start}" && $today <= "{$year}-{$end}";
        });
    }

    private function buildContext(array $stats): array
    {
        return [
            'total_ayahs'    => $stats['totalAyahs'],
            'total_sessions' => $stats['totalSessions'],
            'streak'         => $stats['streak'],
            'avg_quality'    => $stats['avgQuality'],
            'earned_at_date' => now()->toDateString(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\ProgressLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgressLogSeeder extends Seeder
{
    // [surah_number => max_ayah] — representative subset
    private array $surahs = [
        1   => 7,    // Al-Fatiha
        2   => 286,  // Al-Baqarah
        3   => 200,  // Al-Imran
        36  => 83,   // Ya-Sin
        55  => 78,   // Ar-Rahman
        67  => 30,   // Al-Mulk
        78  => 40,   // An-Naba
        112 => 4,    // Al-Ikhlas
        113 => 5,    // Al-Falaq
        114 => 6,    // An-Nas
    ];

    public function run(): void
    {
        $students = User::where('role', 'student')
            ->where('is_banned', false)
            ->whereHas('profile', fn ($q) =>
                $q->whereIn('consent_status', ['not_required', 'approved'])
            )
            ->with('teacherLinks.teacher')
            ->get();

        $count = 0;

        foreach ($students as $student) {
            $activeDays     = rand(10, 28);
            $isHighAchiever = (bool) rand(0, 1);

            // Pick 3–5 surahs this student is working through
            $surahKeys     = collect(array_keys($this->surahs));
            $studentSurahs = $surahKeys->shuffle()->take(rand(3, 5));

            // Get linked teacher for approval
            $teacher = $student->teacherLinks()
                ->where('is_active', true)
                ->with('teacher')
                ->first()?->teacher;

            // Random lesson (optional link)
            $lesson = Lesson::inRandomOrder()->first();

            // Pick random days from the past 30 days
            $daysToLog = collect(range(1, 30))->shuffle()->take($activeDays);

            foreach ($daysToLog as $daysAgo) {
                $date    = now()->subDays($daysAgo)->toDateString();
                $surahNo = $studentSurahs->random();
                $maxAyah = $this->surahs[$surahNo];

                $ayahCount = $isHighAchiever ? rand(5, 20) : rand(1, 10);
                $ayahFrom  = rand(1, max(1, $maxAyah - $ayahCount));
                $ayahTo    = min($ayahFrom + $ayahCount - 1, $maxAyah);
                $quality   = $this->realisticQuality($daysAgo);

                $shouldApprove = $daysAgo > 2 && $teacher && rand(0, 1);

                ProgressLog::create([
                    'user_id'       => $student->id,
                    'lesson_id'     => rand(0, 3) === 0 ? $lesson?->id : null,
                    'surah_number'  => $surahNo,
                    'ayah_from'     => $ayahFrom,
                    'ayah_to'       => $ayahTo,
                    'quality_score' => $quality,
                    'notes'         => $this->randomNote($quality),
                    'logged_at'     => $date,
                    'approved_by'   => $shouldApprove ? $teacher->id : null,
                    'created_at'    => now()->subDays($daysAgo),
                    'updated_at'    => now()->subDays($daysAgo),
                ]);
                $count++;
            }
        }

        $this->command->info("  → ProgressLogSeeder: {$count} progress logs created (30-day history).");
    }

    /**
     * Quality tends to be higher for older logs — student has had time to review.
     */
    private function realisticQuality(int $daysAgo): int
    {
        if ($daysAgo <= 3) {
            return rand(2, 4); // Recent — still fresh
        }
        if ($daysAgo <= 10) {
            return rand(3, 5); // Building confidence
        }
        return rand(4, 5);     // Well-reviewed — high quality
    }

    /**
     * 1-in-3 chance of a student note, quality-matched.
     */
    private function randomNote(int $quality): ?string
    {
        if (rand(0, 2) !== 0) {
            return null;
        }

        $notes = [
            1 => ['Struggled with this section', 'Need more practice', 'Hard ayahs today'],
            2 => ['Getting better slowly', 'Still making some mistakes', 'Need to review again'],
            3 => ['Decent session', 'A few errors but improving', 'Need to work on Tajweed here'],
            4 => ['Good session today', 'Almost perfect', 'Confident but one spot needs work'],
            5 => ['Perfect recitation!', 'Alhamdulillah, very smooth', 'Felt great today!'],
        ];

        return collect($notes[$quality])->random();
    }
}

<?php

namespace App\Console\Commands;

use App\Models\TeacherStudentLink;
use App\Models\User;
use App\Models\ProgressLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyTeacherReports extends Command
{
    protected $signature   = 'reports:weekly-teacher';
    protected $description = 'Email each teacher a weekly summary of their students\' progress';

    public function handle(): int
    {
        $teachers = User::where('role', 'teacher')
            ->whereHas('studentLinks', fn ($q) => $q->where('is_active', true))
            ->with(['studentLinks.student.progressLogs' => function ($q) {
                $q->where('logged_at', '>=', now()->subDays(7));
            }])
            ->get();

        if ($teachers->isEmpty()) {
            $this->info('No active teachers with students found.');
            return self::SUCCESS;
        }

        $sent = 0;

        foreach ($teachers as $teacher) {
            try {
                $studentData = $teacher->studentLinks
                    ->where('is_active', true)
                    ->map(function ($link) {
                        $student = $link->student;
                        $logs    = $student->progressLogs ?? collect();

                        return [
                            'name'        => $student->name,
                            'log_count'   => $logs->count(),
                            'ayahs_total' => $logs->sum(fn ($l) => $l->ayah_to - $l->ayah_from + 1),
                            'avg_quality' => $logs->avg('quality_score') ? round($logs->avg('quality_score'), 1) : '—',
                        ];
                    })
                    ->values();

                Mail::send(
                    'emails.teachers.weekly-report',
                    ['teacher' => $teacher, 'students' => $studentData, 'week_ending' => now()->toFormattedDateString()],
                    fn ($m) => $m
                        ->to($teacher->email, $teacher->name)
                        ->subject('Weekly Student Progress Report — ' . now()->format('d M Y'))
                );

                $sent++;
            } catch (\Throwable $e) {
                $this->error("Failed to send report to teacher #{$teacher->id} ({$teacher->email}): {$e->getMessage()}");
            }
        }

        $this->info("Sent weekly report to {$sent} teacher(s).");

        return self::SUCCESS;
    }
}

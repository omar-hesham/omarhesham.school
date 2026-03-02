<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')
            ->where('is_banned', false)
            ->get();

        $freePrograms    = Program::where('access_level', 'free')->pluck('id');
        $premiumPrograms = Program::where('access_level', 'premium')->pluck('id');

        $count = 0;

        foreach ($students as $student) {
            $isChild = $student->profile?->age_group === 'child';

            // Every non-banned student gets all free programs
            foreach ($freePrograms as $programId) {
                Enrollment::firstOrCreate([
                    'user_id'    => $student->id,
                    'program_id' => $programId,
                ]);
                $count++;
            }

            // 30% of adult students get a premium program (simulates subscriptions)
            if (! $isChild && rand(1, 10) <= 3) {
                $premiumId = $premiumPrograms->random();
                Enrollment::firstOrCreate([
                    'user_id'    => $student->id,
                    'program_id' => $premiumId,
                ]);
                $count++;
            }
        }

        $this->command->info("  → EnrollmentSeeder: {$count} enrollments created.");
    }
}

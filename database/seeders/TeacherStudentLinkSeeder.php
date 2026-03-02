<?php

namespace Database\Seeders;

use App\Models\TeacherStudentLink;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherStudentLinkSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')
            ->where('is_banned', false)
            ->get()
            ->shuffle();

        $count = 0;

        // Distribute students roughly evenly across teachers (~4 students each)
        $studentsPerTeacher = $students->chunk(4);

        foreach ($teachers as $index => $teacher) {
            $batch = $studentsPerTeacher->get($index, collect());

            foreach ($batch as $student) {
                TeacherStudentLink::firstOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'student_id' => $student->id,
                    ],
                    ['is_active' => true]
                );
                $count++;
            }
        }

        $this->command->info("  → TeacherStudentLinkSeeder: {$count} teacher-student links created.");
    }
}

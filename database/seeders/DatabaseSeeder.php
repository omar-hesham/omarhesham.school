<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run order matters — foreign keys must be satisfied before dependents.
     *
     * Environments:
     *   php artisan db:seed                       → full production-quality dataset
     *   php artisan db:seed --class=AdminSeeder   → admin only (safe for prod)
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            // 1. Users (no FK dependencies)
            AdminSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,

            // 2. Badge definitions (no FK dependencies beyond their own tables)
            BadgeSeeder::class,

            // 3. Catalog (depends on admin user as creator)
            ProgramSeeder::class,

            // 3. Relationships (depend on users + programs)
            EnrollmentSeeder::class,
            TeacherStudentLinkSeeder::class,

            // 4. Content (depends on teachers + programs/lessons)
            ContentItemSeeder::class,

            // 5. Activity (depends on students + lessons)
            ProgressLogSeeder::class,

            // 6. Financial (depends on users)
            DonationSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->newLine();
        $this->command->info('✅  omarhesham.school seeded successfully.');
        $this->command->table(
            ['Seeder', 'Records'],
            [
                ['BadgeSeeder',              '48 badge definitions'],
                ['AdminSeeder',              '3 admins'],
                ['TeacherSeeder',            '5 teachers'],
                ['StudentSeeder',            '20 students (15 adult, 5 child)'],
                ['ProgramSeeder',            '5 programs, 25 lessons'],
                ['EnrollmentSeeder',         '~40 enrollments'],
                ['TeacherStudentLinkSeeder', '20 links'],
                ['ContentItemSeeder',        '15 content items'],
                ['ProgressLogSeeder',        '~200 progress logs (30 days)'],
                ['DonationSeeder',           '10 donations'],
            ]
        );
    }
}

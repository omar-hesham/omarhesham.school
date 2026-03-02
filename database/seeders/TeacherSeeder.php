<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    private array $teachers = [
        [
            'name'      => 'Sheikh Mahmoud Al-Azhari',
            'email'     => 'mahmoud.teacher@omarhesham.school',
            'password'  => 'Teacher@2026',
            'locale'    => 'ar',
            'bio'       => 'Hafiz with 15 years of experience teaching Tajweed and Hifz at Al-Azhar University.',
            'specialty' => 'Tajweed & Full Hifz',
        ],
        [
            'name'      => 'Ustadha Fatima Hassan',
            'email'     => 'fatima.teacher@omarhesham.school',
            'password'  => 'Teacher@2026',
            'locale'    => 'en',
            'bio'       => 'Certified Quran teacher specialising in children\'s Hifz programs and Makharij Al-Huruf.',
            'specialty' => 'Children\'s Hifz',
        ],
        [
            'name'      => 'Ustadh Khalid Al-Rashidi',
            'email'     => 'khalid.teacher@omarhesham.school',
            'password'  => 'Teacher@2026',
            'locale'    => 'ar',
            'bio'       => 'Graduate of Islamic University of Madinah, specialising in Warsh and Hafs recitation.',
            'specialty' => 'Advanced Recitation',
        ],
        [
            'name'      => 'Ustadha Amira Youssef',
            'email'     => 'amira.teacher@omarhesham.school',
            'password'  => 'Teacher@2026',
            'locale'    => 'en',
            'bio'       => 'Online Quran instructor with 8 years experience, focusing on beginner and intermediate students.',
            'specialty' => 'Beginner Hifz',
        ],
        [
            'name'      => 'Sheikh Omar Al-Sayed',
            'email'     => 'omar.teacher@omarhesham.school',
            'password'  => 'Teacher@2026',
            'locale'    => 'ar',
            'bio'       => 'Former imam and Quran teacher at a Cairo mosque, now teaching full-time online.',
            'specialty' => 'Tajweed Rules',
        ],
    ];

    public function run(): void
    {
        foreach ($this->teachers as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make($data['password']),
                    'role'              => 'teacher',
                    'email_verified_at' => now(),
                ]
            );

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age_group'      => 'adult',
                    'locale'         => $data['locale'],
                    'consent_status' => 'not_required',
                    'bio'            => $data['bio'],
                ]
            );
        }

        $this->command->info('  → TeacherSeeder: ' . count($this->teachers) . ' teachers created.');
    }
}

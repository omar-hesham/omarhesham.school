<?php

namespace Database\Seeders;

use App\Models\ConsentRecord;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    // 15 adult students
    private array $adults = [
        ['name' => 'Ahmed Al-Rashid',   'email' => 'ahmed@student.test',    'locale' => 'ar'],
        ['name' => 'Mohammed Karimi',   'email' => 'mohammed@student.test', 'locale' => 'ar'],
        ['name' => 'Sarah Al-Mansouri', 'email' => 'sarah@student.test',    'locale' => 'en'],
        ['name' => 'Ali Hassan',        'email' => 'ali@student.test',      'locale' => 'ar'],
        ['name' => 'Nadia Benali',      'email' => 'nadia@student.test',    'locale' => 'en'],
        ['name' => 'Ibrahim Al-Farsi',  'email' => 'ibrahim@student.test',  'locale' => 'ar'],
        ['name' => 'Zainab Osman',      'email' => 'zainab@student.test',   'locale' => 'en'],
        ['name' => 'Hassan Al-Nuaimi',  'email' => 'hassan@student.test',   'locale' => 'ar'],
        ['name' => 'Mariam Khalil',     'email' => 'mariam@student.test',   'locale' => 'en'],
        ['name' => 'Yusuf Al-Qasim',   'email' => 'yusuf@student.test',    'locale' => 'ar'],
        ['name' => 'Aisha Diallo',      'email' => 'aisha@student.test',    'locale' => 'en'],
        ['name' => 'Tariq Bouazizi',    'email' => 'tariq@student.test',    'locale' => 'ar'],
        ['name' => 'Leilani Saleh',     'email' => 'leilani@student.test',  'locale' => 'en'],
        ['name' => 'Khalid Mubarak',    'email' => 'khalid@student.test',   'locale' => 'ar'],
        ['name' => 'Rania Ibrahim',     'email' => 'rania@student.test',    'locale' => 'en'],
    ];

    // 5 child students (requires parental consent)
    private array $children = [
        [
            'name'           => 'Yusuf Al-Rashid Jr.',
            'email'          => 'yusuf.jr@student.test',
            'guardian_email' => 'parent.yusuf@guardian.test',
            'consent_status' => 'approved',
        ],
        [
            'name'           => 'Amira Hassan',
            'email'          => 'amira@student.test',
            'guardian_email' => 'parent.amira@guardian.test',
            'consent_status' => 'approved',
        ],
        [
            'name'           => 'Omar Junior',
            'email'          => 'omar.jr@student.test',
            'guardian_email' => 'parent.omar@guardian.test',
            'consent_status' => 'pending',
        ],
        [
            'name'           => 'Fatima Al-Zahra',
            'email'          => 'fatima.child@student.test',
            'guardian_email' => 'parent.fatima@guardian.test',
            'consent_status' => 'approved',
        ],
        [
            'name'           => 'Bilal Siddiq',
            'email'          => 'bilal.child@student.test',
            'guardian_email' => 'parent.bilal@guardian.test',
            'consent_status' => 'denied',
        ],
    ];

    public function run(): void
    {
        // ── Adult Students ──────────────────────────────────────────────────
        foreach ($this->adults as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('Student@2026'),
                    'role'              => 'student',
                    'email_verified_at' => now(),
                ]
            );

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age_group'      => 'adult',
                    'locale'         => $data['locale'],
                    'consent_status' => 'not_required',
                ]
            );
        }

        // ── Child Students ──────────────────────────────────────────────────
        foreach ($this->children as $data) {
            $isBanned = $data['consent_status'] === 'denied';

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('Student@2026'),
                    'role'              => 'student',
                    'email_verified_at' => now(),
                    'is_banned'         => $isBanned,
                    'ban_reason'        => $isBanned ? 'Parental consent denied.' : null,
                ]
            );

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age_group'      => 'child',
                    'locale'         => 'en',
                    'guardian_email' => $data['guardian_email'],
                    'consent_status' => $data['consent_status'],
                ]
            );

            ConsentRecord::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'consent_token'  => Str::random(64),
                    'action'         => match ($data['consent_status']) {
                        'approved' => 'approved',
                        'denied'   => 'denied',
                        default    => 'requested',
                    },
                    'guardian_email' => $data['guardian_email'],
                    'responded_at'   => in_array($data['consent_status'], ['approved', 'denied'])
                        ? now()->subDays(rand(1, 10))
                        : null,
                    'created_at'     => now()->subDays(rand(1, 14)),
                    'updated_at'     => now(),
                ]
            );
        }

        $this->command->info('  → StudentSeeder: 15 adults + 5 children created.');
        $this->command->line('  <fg=cyan>  Password: Student@2026</>');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name'      => 'Omar Hesham',
                'email'     => 'omar@omarhesham.school',
                'password'  => 'Admin@12345',
                'role'      => 'admin',
                'age_group' => 'adult',
                'locale'    => 'ar',
                'bio'       => 'Founder & Head of omarhesham.school Quran memorization platform.',
            ],
            [
                'name'      => 'Layla Ibrahim',
                'email'     => 'layla@omarhesham.school',
                'password'  => 'CenterAdmin@123',
                'role'      => 'center_admin',
                'age_group' => 'adult',
                'locale'    => 'en',
                'bio'       => 'Center Administrator — manages daily operations and content moderation.',
            ],
            [
                'name'      => 'Support Team',
                'email'     => 'support@omarhesham.school',
                'password'  => 'Support@99999',
                'role'      => 'center_admin',
                'age_group' => 'adult',
                'locale'    => 'en',
                'bio'       => 'Platform support and user assistance.',
            ],
        ];

        foreach ($admins as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make($data['password']),
                    'role'              => $data['role'],
                    'email_verified_at' => now(),
                ]
            );

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'age_group'      => $data['age_group'],
                    'locale'         => $data['locale'],
                    'consent_status' => 'not_required',
                    'bio'            => $data['bio'],
                ]
            );
        }

        $this->command->info('  → AdminSeeder: 3 admin users created/updated.');
        $this->command->line('');
        $this->command->line('  <fg=yellow>╔══════════════════════════════════════╗</>');
        $this->command->line('  <fg=yellow>║        DEMO LOGIN CREDENTIALS        ║</>');
        $this->command->line('  <fg=yellow>╠══════════════════════════════════════╣</>');
        $this->command->line('  <fg=yellow>║  Admin:    omar@omarhesham.school    ║</>');
        $this->command->line('  <fg=yellow>║  Password: Admin@12345               ║</>');
        $this->command->line('  <fg=yellow>╚══════════════════════════════════════╝</>');
        $this->command->line('');
    }
}

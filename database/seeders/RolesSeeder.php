<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Seed the initial admin user and verify all role enum values are
     * consistent with the users table migration.
     *
     * Run via: php artisan db:seed --class=RolesSeeder
     *
     * Roles defined in users.role enum:
     *   student | teacher | admin | center_admin
     */
    public function run(): void
    {
        // Create the platform super-admin if not already present
        User::firstOrCreate(
            ['email' => 'omar@omarhesham.school'],
            [
                'name'     => 'Omar Hesham',
                'password' => Hash::make(env('ADMIN_INITIAL_PASSWORD', 'ChangeMe123!')),
                'role'     => 'admin',
            ]
        );

        // Create a default center_admin account
        User::firstOrCreate(
            ['email' => 'admin@omarhesham.school'],
            [
                'name'     => 'Center Admin',
                'password' => Hash::make(env('ADMIN_INITIAL_PASSWORD', 'ChangeMe123!')),
                'role'     => 'center_admin',
            ]
        );

        $this->command->info('✅ Admin and center_admin accounts seeded.');
        $this->command->warn('⚠️  Remember to change default passwords immediately!');
    }
}

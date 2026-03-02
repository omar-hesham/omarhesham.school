<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'role'              => 'student',
            'is_banned'         => false,
            'ban_reason'        => null,
            'remember_token'    => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }

    public function teacher(): static
    {
        return $this->state(['role' => 'teacher']);
    }

    public function student(): static
    {
        return $this->state(['role' => 'student']);
    }

    public function banned(string $reason = 'Policy violation.'): static
    {
        return $this->state([
            'is_banned'  => true,
            'ban_reason' => $reason,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }
}

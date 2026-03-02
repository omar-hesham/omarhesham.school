<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressLogFactory extends Factory
{
    public function definition(): array
    {
        $surahNo  = fake()->numberBetween(1, 114);
        $ayahFrom = fake()->numberBetween(1, 50);
        $ayahTo   = $ayahFrom + fake()->numberBetween(0, 15);

        return [
            'user_id'       => User::factory()->student(),
            'lesson_id'     => null,
            'surah_number'  => $surahNo,
            'ayah_from'     => $ayahFrom,
            'ayah_to'       => $ayahTo,
            'quality_score' => fake()->numberBetween(1, 5),
            'notes'         => fake()->optional(0.3)->sentence(),
            'logged_at'     => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'approved_by'   => null,
        ];
    }

    public function approved(int $teacherId): static
    {
        return $this->state(['approved_by' => $teacherId]);
    }

    public function today(): static
    {
        return $this->state(['logged_at' => now()->toDateString()]);
    }
}

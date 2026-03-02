<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => null,
            'amount'       => fake()->randomElement([5, 10, 25, 50, 100]),
            'currency'     => 'usd',
            'type'         => fake()->randomElement(['one_time', 'recurring']),
            'stripe_pi_id' => 'pi_test_' . Str::random(20),
            'status'       => 'completed',
            'donor_name'   => fake()->optional(0.4)->name(),
        ];
    }

    public function completed(): static { return $this->state(['status' => 'completed']); }
    public function pending(): static   { return $this->state(['status' => 'pending']); }
    public function failed(): static    { return $this->state(['status' => 'failed']); }
    public function recurring(): static { return $this->state(['type' => 'recurring']); }
}

<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'student')
            ->where('is_banned', false)
            ->whereHas('profile', fn ($q) => $q->where('age_group', 'adult'))
            ->inRandomOrder()
            ->take(8)
            ->get();

        $donations = [
            ['amount' => 25.00,  'type' => 'one_time',  'status' => 'completed', 'name' => 'Anonymous'],
            ['amount' => 50.00,  'type' => 'one_time',  'status' => 'completed', 'name' => null],
            ['amount' => 10.00,  'type' => 'recurring', 'status' => 'completed', 'name' => 'A Muslim Brother'],
            ['amount' => 100.00, 'type' => 'one_time',  'status' => 'completed', 'name' => null],
            ['amount' => 10.00,  'type' => 'recurring', 'status' => 'completed', 'name' => null],
            ['amount' => 5.00,   'type' => 'one_time',  'status' => 'completed', 'name' => 'Anonymous'],
            ['amount' => 25.00,  'type' => 'recurring', 'status' => 'completed', 'name' => null],
            ['amount' => 200.00, 'type' => 'one_time',  'status' => 'completed', 'name' => 'A Generous Donor'],
            ['amount' => 15.00,  'type' => 'one_time',  'status' => 'failed',    'name' => null],
            ['amount' => 50.00,  'type' => 'one_time',  'status' => 'pending',   'name' => null],
        ];

        foreach ($donations as $index => $data) {
            $user = $users->get($index % $users->count());

            Donation::create([
                'user_id'      => $user?->id,
                'amount'       => $data['amount'],
                'currency'     => 'usd',
                'type'         => $data['type'],
                'stripe_pi_id' => 'pi_seed_' . strtolower(Str::random(20)),
                'status'       => $data['status'],
                'donor_name'   => $data['name'],
                'created_at'   => now()->subDays(rand(1, 90)),
                'updated_at'   => now(),
            ]);
        }

        $total = collect($donations)->where('status', 'completed')->sum('amount');
        $this->command->info("  → DonationSeeder: 10 donations created (\${$total} total completed).");
    }
}

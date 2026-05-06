<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'pickup_datetime' => now(),
            'return_datetime' => now()->addDays(2),
            'total_days' => 2,
            'total_price' => 100000,
            'payment_status' => 'paid',
            'status' => 'active',
        ];
    }
}

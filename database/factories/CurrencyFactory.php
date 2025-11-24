<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'currency_code' => $this->faker->unique()->currencyCode,
            'currency_name' => $this->faker->words(2, true) . ' Currency', // e.g. "Sample Currency"
            'exchange_rate' => $this->faker->randomFloat(4, 0.1, 10),
            'user_id' => User::factory(),
        ];
    }
}

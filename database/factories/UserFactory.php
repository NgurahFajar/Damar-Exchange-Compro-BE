<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'user' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'), // Anda bisa mengubah password default jika diperlukan
            'remember_token' => Str::random(100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

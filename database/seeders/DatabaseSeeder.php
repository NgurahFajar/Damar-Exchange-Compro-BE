<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $this->call(UserSeeder::class);
        $this->call([
            CurrencySeeder::class,
        ]);
        // If you want to add more test users, you can uncomment and modify this
        // User::factory(10)->create();
    }
}

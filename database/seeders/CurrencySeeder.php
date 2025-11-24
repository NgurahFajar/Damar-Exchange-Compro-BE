<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            [
                'currency_code' => 'USD',
                'currency_name' => 'United States Dollar',
                'buy_rate'      => 15500.50,
                'sell_rate'     => 15600.75,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/usd.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'buy_rate'      => 16500.20,
                'sell_rate'     => 16700.40,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/eur.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'GBP',
                'currency_name' => 'British Pound Sterling',
                'buy_rate'      => 19500.20,
                'sell_rate'     => 19700.40,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/gbp.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'JPY',
                'currency_name' => 'Japanese Yen',
                'buy_rate'      => 105.25,
                'sell_rate'     => 107.10,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/jpy.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'CNY',
                'currency_name' => 'Chinese Yuan',
                'buy_rate'      => 2200.00,
                'sell_rate'     => 2250.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/cny.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'SGD',
                'currency_name' => 'Singapore Dollar',
                'buy_rate'      => 11600.00,
                'sell_rate'     => 11800.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/sgd.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'AUD',
                'currency_name' => 'Australian Dollar',
                'buy_rate'      => 10300.00,
                'sell_rate'     => 10500.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/aud.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'CAD',
                'currency_name' => 'Canadian Dollar',
                'buy_rate'      => 11500.00,
                'sell_rate'     => 11750.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/cad.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'HKD',
                'currency_name' => 'Hong Kong Dollar',
                'buy_rate'      => 2000.00,
                'sell_rate'     => 2050.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/hkd.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'MYR',
                'currency_name' => 'Malaysian Ringgit',
                'buy_rate'      => 3300.00,
                'sell_rate'     => 3400.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/myr.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'THB',
                'currency_name' => 'Thai Baht',
                'buy_rate'      => 450.00,
                'sell_rate'     => 480.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/thb.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'KRW',
                'currency_name' => 'South Korean Won',
                'buy_rate'      => 12.00,
                'sell_rate'     => 14.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/krw.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'PHP',
                'currency_name' => 'Philippine Peso',
                'buy_rate'      => 270.00,
                'sell_rate'     => 290.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/php.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'VND',
                'currency_name' => 'Vietnamese Dong',
                'buy_rate'      => 0.60,
                'sell_rate'     => 0.70,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/vnd.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'INR',
                'currency_name' => 'Indian Rupee',
                'buy_rate'      => 180.00,
                'sell_rate'     => 190.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/inr.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'BRL',
                'currency_name' => 'Brazilian Real',
                'buy_rate'      => 2900.00,
                'sell_rate'     => 3000.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/brl.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'RUB',
                'currency_name' => 'Russian Ruble',
                'buy_rate'      => 170.00,
                'sell_rate'     => 180.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/rub.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'NZD',
                'currency_name' => 'New Zealand Dollar',
                'buy_rate'      => 9000.00,
                'sell_rate'     => 9300.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/nzd.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'currency_code' => 'CHF',
                'currency_name' => 'Swiss Franc',
                'buy_rate'      => 17600.00,
                'sell_rate'     => 17850.00,
                'user_id'       => 1,
                'icon_data'     => file_get_contents(public_path('flag/chf.png')),
                'icon_type'     => 'image/png',
                'is_deleted'    => false,

                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}

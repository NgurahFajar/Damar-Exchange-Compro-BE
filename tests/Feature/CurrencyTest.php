<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Currency;
use Database\Factories\CurrencyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    protected function getToken()
    {
        $user = User::factory()->create();
        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_can_get_all_currencies()
    {
        $token = $this->getToken();

        Currency::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/currencies');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_currency()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $currencyData = [
            'currency_code' => 'USD',
            'currency_name' => 'US Dollar',
            'exchange_rate' => 1.0
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/currencies', $currencyData);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => [
                'currency_code',
                'currency_name',
                'exchange_rate',
                'user_id'
            ]]);

        $this->assertDatabaseHas('currencies', [
            'currency_code' => 'USD',
            'user_id' => $user->id
        ]);
    }

    public function test_cannot_create_currency_with_invalid_data()
    {
        $token = $this->getToken();

        $invalidData = [
            'currency_code' => '',
            'currency_name' => '',
            'exchange_rate' => 'not a number'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/currencies', $invalidData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['currency_code', 'currency_name', 'exchange_rate']);
    }

    public function test_can_update_existing_currency()
    {
        $token = $this->getToken();
        $currency = Currency::factory()->create();

        $updateData = [
            'currency_name' => 'Updated Currency Name',
            'exchange_rate' => 1.5
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/currencies/{$currency->currency_code}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['data' => $updateData]);

        $this->assertDatabaseHas('currencies', [
            'currency_code' => $currency->currency_code,
            'currency_name' => 'Updated Currency Name',
            'exchange_rate' => 1.5
        ]);
    }

    public function test_can_delete_currency()
    {
        $token = $this->getToken();
        $currency = Currency::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/currencies/{$currency->currency_code}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('currencies', ['currency_code' => $currency->currency_code]);
    }

    public function test_can_fetch_specific_currency()
    {
        $token = $this->getToken();
        $currency = Currency::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/currencies/{$currency->currency_code}");

        $response->assertStatus(200)
            ->assertJson(['data' => [
                'currency_code' => $currency->currency_code,
                'currency_name' => $currency->currency_name,
                'exchange_rate' => $currency->exchange_rate
            ]]);
    }
}

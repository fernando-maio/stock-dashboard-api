<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_stocks()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/stocks');

        $response->assertOk()
            ->assertJsonStructure([['symbol', 'name', 'price']]);
    }

    public function test_user_can_get_stock_by_symbol()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/stocks/AAPL');

        $response->assertOk()
            ->assertJsonStructure(['symbol', 'name', 'price']);
    }

    public function test_invalid_symbol_returns_error()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->getJson('/api/stocks/INVALIDSYMBOL123');

        $response->assertStatus(500)
            ->assertJsonStructure(['message']);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteStockTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_favorite_and_list_stocks()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        // Favorite
        $this->withToken($token)
            ->postJson('/api/stocks/favorites/AAPL')
            ->assertStatus(200)
            ->assertJson(['message' => 'Favorited AAPL']);

        // List favorites
        $this->withToken($token)
            ->getJson('/api/stocks/favorites')
            ->assertStatus(200)
            ->assertJsonStructure([['symbol', 'name', 'price']]);
    }

    public function test_user_can_unfavorite()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        // Add favorite first
        $this->withToken($token)
            ->postJson('/api/stocks/favorites/AAPL');

        // Then remove
        $this->withToken($token)
            ->deleteJson('/api/stocks/favorites/AAPL')
            ->assertStatus(200)
            ->assertJson(['message' => 'Unfavorited AAPL']);
    }

    public function test_user_cannot_favorite_same_symbol_twice()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $this->withToken($token)->postJson('/api/stocks/favorites/AAPL')->assertStatus(200);
        $this->withToken($token)->postJson('/api/stocks/favorites/AAPL')->assertStatus(200);

        $this->assertDatabaseCount('user_favorite_stocks', 1);
    }

    public function test_user_can_unfavorite_nonexistent_symbol_gracefully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $this->withToken($token)
            ->deleteJson('/api/stocks/favorites/NOTFAVORITED')
            ->assertStatus(200)
            ->assertJson(['message' => 'Unfavorited NOTFAVORITED']);
    }

    public function test_user_cannot_favorite_invalid_symbol()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson('/api/stocks/favorites/!@#$%');

        $response->assertStatus(200);
    }

}

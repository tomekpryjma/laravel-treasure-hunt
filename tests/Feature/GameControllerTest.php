<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;
    
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = \Faker\Factory::create();
    }

    public function test_store_games_validation_fails()
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        
        $response = $this->postJson(route('game.store'));

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'title' => ['The title field is required.',]
                ]
            ]);
    }

    public function test_store_games_validates_and_stores()
    {
        $gameTitle = self::$faker->city();

        $user = User::factory()->create();

        $this->actingAs($user);
        
        $response = $this->postJson(route('game.store'), [
            'title' => $gameTitle,
        ]);

        $response->assertStatus(201);

    }
}

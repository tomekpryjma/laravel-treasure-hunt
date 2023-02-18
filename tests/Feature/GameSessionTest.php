<?php

namespace Tests\Feature;

use App\Models\GameSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GameSessionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_game_session_gets_lobbies_and_players()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // Create game
        $response = $this->postJson(route('game.store'), [
            'title' => $this->faker->city(),
        ]);
        $response->assertStatus(201);

        // Create steps
        $stepData1 = [
            'title' => 'My Step 1',
            'messages' => ['Message 1', 'Message 2'],
            'accepted_answers' => ['answer1', 'Answer1', 'Answer2'],
        ];

        $response = $this->postJson(route('step.store', ['game' => $user->games()->first()]), $stepData1);
        $response->assertStatus(201);
        $stepData2 = [
            'title' => 'My Step 2',
            'messages' => ['My Step 2 Message 1', 'My Step 2 Message 2'],
            'accepted_answers' => ['My Step 2 Answer 1', 'My Step 2 Answer 2',],
        ];

        $response = $this->postJson(route('step.store', ['game' => $user->games()->first()]), $stepData2);
        $response->assertStatus(201);

        // Logout admin user
        Auth::logout();
        $this->assertGuest();

        // Player registration
        $playerEmails = [
            $this->faker->email(),
            $this->faker->email(),
            $this->faker->email(),
        ];
        $response = $this->post(route('game.register'), [
            'emails' => $playerEmails,
            'game_id' => $user->games()->first()->id,
        ]);
        $response->assertStatus(200);

        // test mail out

        // Test DB refreshes after each test case, should only be 3 players.
        $players = Player::all();

        $gameSession = GameSession::where('game_id', $user->games()->first()->id)->first();
        $this->assertNotNull($gameSession);
        $this->assertNotEmpty($gameSession->session_id);
        $this->assertFalse($gameSession->in_progress);
        $this->assertNull($gameSession->completed_at);
        $this->assertNotEquals(0, $gameSession->game()->steps()->count());
        $this->assertEquals(count($playerEmails), $gameSession->lobbies()->count());

        $this->assertEquals(count($playerEmails), $players->count());
        foreach ($players as $player) {
            $this->assertEquals($gameSession->id, $player->lobby->gameSession->id);
        }
    }
}

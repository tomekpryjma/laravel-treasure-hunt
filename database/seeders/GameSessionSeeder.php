<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameSession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = Game::all();

        foreach ($games as $game) {
            GameSession::factory()->count(1)->for($game)->create();
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\GameSession;
use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', config('custom.test_admin_email'))->first();
        $sessionCount = 5;
        $playerCountMin = 1;
        $playerCountMax = 6;
        $game = $user->games()->first();

        for ($i = 0; $i < $sessionCount; $i++) {
            $gameSession = GameSession::factory()->for($game)->create();
            Player::factory()->count(rand($playerCountMin, $playerCountMax))->for($gameSession)->create();
        }
    }
}

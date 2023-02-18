<?php

namespace Database\Seeders;

use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', config('custom.test_admin_email'))->first();
        foreach ($user->games as $game) {
            Step::factory()->count(5)->for($game)->create();
        }
    }
}

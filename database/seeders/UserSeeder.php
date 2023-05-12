<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', config('custom.test_admin_email'))->first()) {
            return;
        }
        \App\Models\User::factory()->create([
            'email' => config('custom.test_admin_email'),
            'name' => 'Admin',
            'password' => bcrypt('password'),
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'session_code' => $this->faker->uuid(),
            'access_code' => $this->faker->numerify('######'),
            'in_progress' => false,
        ];
    }

    public function ongoing()
    {
        return $this->state(function (array $attributes) {
            return ['in_progress' => true];
        });
    }
}

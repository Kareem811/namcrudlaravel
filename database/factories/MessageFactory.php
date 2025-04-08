<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->userName(),
            'email' => fake()->email(),
            'message' => fake()->paragraph(),
            // 'reply' => fake()->optional()->paragraph(),
            'reply' => fake()->optional(0.5, 'No Reply')->paragraph(),

        ];
    }
}

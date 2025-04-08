<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category' => $this->faker->sentence(),
            // Generate a longer description with multiple paragraphs
            'description' => implode(' ', $this->faker->paragraphs(mt_rand(2, 4))),
            'images' => json_encode($this->faker->randomElements(
                [
                    $this->faker->imageUrl(640, 480, 'products', true, 'Product 1'),
                    $this->faker->imageUrl(640, 480, 'products', true, 'Product 2'),
                    $this->faker->imageUrl(640, 480, 'products', true, 'Product 3'),
                    $this->faker->imageUrl(640, 480, 'products', true, 'Product 4')
                ],
                $count = mt_rand(2, 3)
            )),
        ];
    }
}

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
            'category_id' => \App\Models\Category::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price_per_day' => $this->faker->numberBetween(10000, 100000),
            'stock' => 10,
        ];
    }
}

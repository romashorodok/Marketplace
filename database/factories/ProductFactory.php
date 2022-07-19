<?php

namespace Database\Factories;

use App\Models\Category;
use Database\Factories\Providers\ProductNameProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator;

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
    public function definition()
    {
        return [
            'name' => $this->faker->productName,
            'price' => fake()->randomFloat(100, 1, 1000),
            'count' => fake()->numberBetween(0, 5),
            'category_id' => Category::factory()
        ];
    }

    protected function withFaker(): Generator
    {
        $fake = parent::withFaker();
        $fake->addProvider(ProductNameProvider::class);
        return $fake;
    }
}

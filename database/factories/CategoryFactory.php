<?php

namespace Database\Factories;

use Database\Factories\Providers\CategoryNameProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->categoryName
        ];
    }

    protected function withFaker()
    {
        $fake = parent::withFaker();
        $fake->addProvider(CategoryNameProvider::class);
        return $fake;
    }
}

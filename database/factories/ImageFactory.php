<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Providers\ImageProvider;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'image_path' => Storage::disk('public')->putFile('images', $this->faker->imagePath)
        ];
    }

    protected function withFaker()
    {
        $fake = parent::withFaker();
        $fake->addProvider(ImageProvider::class);
        return $fake;
    }
}

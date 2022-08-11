<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::factory(10)->create();

        Product::factory(400)
            ->create([
                'image_id' => Image::factory()
            ])->each(function (Product $product) use ($categories) {
                $product->update([
                    'category_id' => $categories->random()->id
                ]);
            });

        User::factory(10)->create();

//        $this->test_users();
    }

    public function test_users()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}

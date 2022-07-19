<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
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
        $this->test_users();

        User::factory(10)->create();

        Product::factory(10)->create();
    }

    public function test_users() {
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

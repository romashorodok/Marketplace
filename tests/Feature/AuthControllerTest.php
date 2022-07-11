<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /** @test */
    public function validate_fake_access_token()
    {
        $user = [
            "email"    => "admin@example.com",
            "password" => "password"
        ];

        $user1 = [
            "email"    => "test@example.com",
            "password" => "password"
        ];

        $response = $this->post('/api/login', $user);

        $fake_token = $response->decodeResponseJson()['token'];

        $response = $this->withHeaders([
            "Authorization" => "Bearer " . $fake_token
        ])->get('/api/token', $user);

        $response->assertStatus(200);

        $this->get('/api/logout');

        /**
         * Login with other user access token
         */
        $this->post('/api/login', $user1);

        $response = $this->withHeaders([
            "Authorization" => "Bearer " . $fake_token
        ])->get('/api/token', $user);

        /**
         * Check if user was logged out
         */
        $response
            ->assertStatus(403)
            ->assertJson([
                "status" => "BAD"
            ]);

        /**
         * User logged out
         */
        $this->get('/api/logout')
            ->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorized"
            ]);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register()
    {
        $response = $this->json('POST', '/api/register', [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->dump();

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'api_token'
            ]
        ]);
    }
}

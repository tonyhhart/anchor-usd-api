<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factoryCreate();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->dump();

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'api_token'
        ]);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->dump();

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
    }
}

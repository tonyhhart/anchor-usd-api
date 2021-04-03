<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factoryCreate([
            'password' => Hash::make('12345678')
        ]);

        $response = $this->json('POST', '/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->dump();

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'api_token'
            ]
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

<?php

namespace Tests\Feature;

use App\Models\Coin;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInfoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_endpoint()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::factoryCreate();

        $data = [
            'coins_order' => Coin::all()->pluck('id')
        ];

        $response = $this->withHeaders(['Authorization' => "Bearer {$user->api_token}"])
            ->json('POST', '/api/user-infos', $data);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'data' => [
                "id",
                "user_id",
                "coins_order",
                "created_at",
                "updated_at",
            ]
        ]);
    }
}

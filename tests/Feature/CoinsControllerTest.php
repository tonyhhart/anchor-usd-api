<?php

namespace Tests\Feature;

use App\Models\Coin;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoinsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_endpoint()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::factoryCreate();

        $response = $this->withHeaders(['Authorization' => "Bearer {$user->api_token}"])
            ->json('GET', '/api/coins');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                [
                    "id",
                    "name",
                    "symbol",
                    "coinname",
                    "fullname",
                    "description",
                    "image",
                    "usd_price",
                    "usd_change_pct_day",
                    "usd_change_pct_24_hours",
                    "usd_change_pct_hour",
                    "created_at",
                    "updated_at",
                    "deleted_at",
                    "image_url",
                ]
            ]
        ]);
    }

    public function test_show_endpoint()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::factoryCreate();

        $coin = Coin::query()->inRandomOrder()->first();

        $response = $this->withHeaders(['Authorization' => "Bearer {$user->api_token}"])
            ->json('GET', "/api/coins/{$coin->id}");

        $response->dump();

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "symbol",
                "coinname",
                "fullname",
                "description",
                "image",
                "usd_price",
                "usd_change_pct_day",
                "usd_change_pct_24_hours",
                "usd_change_pct_hour",
                "created_at",
                "updated_at",
                "deleted_at",
                "image_url",
            ]
        ]);
    }
}

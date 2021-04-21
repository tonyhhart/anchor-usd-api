<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_endpoint()
    {
        $user = User::factoryCreate();

        $response = $this->withHeaders(['Authorization' => "Bearer {$user->api_token}"])
            ->json('GET', '/api/index');

        $response->dump();

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                [
                    'CoinModel' => [
                        "id",
                        "name",
                        "fullname",
                        "internal",
                        "image",
                        "image_url",
                    ],
                    'CoinInfo'  => [
                        "Id",
                        "Name",
                        "FullName",
                        "Internal",
                        "ImageUrl",
                        "Url",
                        "Algorithm",
                        "ProofType",
                    ]
                ]
            ]
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Coin;
use App\Services\CryptoCompareAPI;
use Illuminate\Database\Seeder;

class CoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = CryptoCompareAPI::index('100');

        if ($response->json('Message') === 'Success') {
            foreach ($response->json('Data') as $data) {
                Coin::createFromApi($data);
            }
        }
    }
}

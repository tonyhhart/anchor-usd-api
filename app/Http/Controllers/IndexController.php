<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Services\CryptoCompareAPI;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        return Cache::remember('index', now()->addMinute(), function () {
            $response = CryptoCompareAPI::index();

            if ($response->json('Message') === 'Success') {
                foreach ($response->json('Data') as $data) {
                    Coin::createFromApi($data);
                }

                $coins = Coin::all()->keyBy('id');

                return ['data' => collect($response->json('Data'))->map(fn($data) => ['CoinModel' => $coins->get($data['CoinInfo']['Id'])] + $data)];
            }

            return [];
        });
    }
}

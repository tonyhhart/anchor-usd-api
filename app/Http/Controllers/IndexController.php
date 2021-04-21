<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoinResource;
use App\Models\Coin;
use App\Services\CryptoCompareAPI;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    public function index()
    {
        $coins = Cache::remember('index', now()->addMinute(), function () {
            $response = CryptoCompareAPI::prices();

            foreach ($response->json('RAW', []) as $symbol => $data) {
                Coin::query()->whereSymbol($symbol)
                    ->update([
                        'usd_price' => $data['USD']['PRICE'] ?? 0,
                        'usd_change_pct_day' => $data['USD']['CHANGEPCTDAY'] ?? 0,
                        'usd_change_pct_24_hours' => $data['USD']['CHANGEPCT24HOUR'] ?? 0,
                        'usd_change_pct_hour' => $data['USD']['CHANGEPCTHOUR'] ?? 0,
                    ]);
            }

            return Coin::all();
        });

        return CoinResource::collection($coins);
    }
}

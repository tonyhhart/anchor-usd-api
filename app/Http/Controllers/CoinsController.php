<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoinResource;
use App\Models\Coin;
use App\Services\CryptoCompareAPI;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CoinsController extends Controller
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


    public function show($id)
    {
        $coin = Coin::query()->firstOrFail();

        $coin->historic = Cache::remember("show_$id", now()->addMinute(), function () use ($coin) {
            $response = CryptoCompareAPI::history($coin->symbol);

            if ($response->json('Response') !== 'Success') {
                return [];
            }

            return collect($response->json('Data'))
                ->filter(fn($d) => Carbon::createFromTimestamp($d['time'])->minute % 15 === 0)
                ->map(fn($d) => ['time' => $d['time'], 'close' => $d['close']])
                ->toArray();
        });

        return CoinResource::make($coin, 1);
    }
}

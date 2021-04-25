<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoinResource;
use App\Models\Coin;
use App\Services\CryptoCompareAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        $ids = current_user()->info->coins_order ?? [];

        $coins = $coins->filter(fn(Coin $c) => in_array($c->id, $ids))
            ->sortBy(fn (Coin $c) => array_search($c->id, $ids))
            ->concat(
                $coins->filter(fn(Coin $c) => !in_array($c->id, $ids))
            );

        return CoinResource::collection($coins);
    }


    public function show(Request $request, $id)
    {
        $period = $request->get('period', 'day');
        $coin = Coin::query()->find($id);

        $coin->historic = Cache::remember("show_{$period}_{$id}", now()->addMinute(), function () use ($coin, $period) {
            $response = CryptoCompareAPI::history($coin->symbol, $period);

            if ($response->json('Response') !== 'Success') {
                return [];
            }

            return collect($response->json('Data'))
                ->filter(fn($d) => match ($period) {
                    'day' => Carbon::createFromTimestamp($d['time'])->minute % 15 === 0,
                    'month' => Carbon::createFromTimestamp($d['time'])->hour % 4 === 0,
                    default => true,
                })
                ->map(fn($d) => ['time' => $d['time'], 'close' => $d['close']])
                ->toArray();
        });

        return CoinResource::make($coin);
    }
}

<?php


namespace App\Services;


use Illuminate\Support\Facades\Http;

class CryptoCompareAPI
{

    private const ENDPOINT = 'https://min-api.cryptocompare.com/data/';

    public static function get($endpoint, $query = []): \Illuminate\Http\Client\Response
    {
        return Http::asJson()
            ->withHeaders([
                'Apikey' => env('CRYPTO_COMPARE_API_KEY')
            ])
            ->get(self::ENDPOINT . $endpoint, $query);
    }

    public static function index($limit = '10'): \Illuminate\Http\Client\Response
    {
        return self::get('top/totalvolfull', [
            'limit' => $limit,
            'tsym'  => 'USD',
        ]);
    }

    public static function coinlist(): \Illuminate\Http\Client\Response
    {
        return self::get('all/coinlist');
    }

    public static function prices($tsym = ['USD']): \Illuminate\Http\Client\Response
    {
        return self::get('pricemultifull', [
            'fsyms' => implode(',', config('app.available_coins', [])),
            'tsyms' => implode(',', $tsym),
        ]);
    }

    public static function history($from, $period, $to = 'USD'): \Illuminate\Http\Client\Response
    {
        $endpoint = match ($period) {
            'hour', 'day' => 'histominute',
            'week', 'month' => 'histohour',
        };

        $limit = match ($period) {
            'hour' => 60,
            'day' => 60 * 24,
            'week' => 24 * 7,
            'month' => 24 * 30,
        };

        return self::get($endpoint, [
            'fsym'  => $from,
            'tsym'  => $to,
            'limit' => $limit
        ]);
    }

}

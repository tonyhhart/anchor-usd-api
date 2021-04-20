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

    public static function index()
    {
        return self::get('top/totalvolfull', [
            'limit' => '10',
            'tsym' => 'USD',
        ])->json();
    }

}

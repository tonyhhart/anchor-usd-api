<?php

namespace App\Models;

use App\Traits\HasTypedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coin extends Model
{
    use HasFactory, HasTypedFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'fullname',
        'internal',
        'image',
        'type',
        'documenttype',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute($key)
    {
        return asset($this->image);
    }

    public static function createFromApi($data)
    {
        $data = array_change_key_case($data['CoinInfo'], CASE_LOWER);

        [, , , $image] = explode('/', $data['imageurl']);

        $path = resource_path("img/coins/{$image}");
        $url = "https://www.cryptocompare.com{$data['imageurl']}";

        if (!file_exists($path)) {
            @file_put_contents($path, file_get_contents($url));
        }

        return self::query()->updateOrCreate(['id' => $data['id']],
            collect($data)
                ->merge(['image' => "/img/coins/{$image}"])->only([
                    'id',
                    'name',
                    'fullname',
                    'internal',
                    'image',
                    'type',
                    'documenttype',
                ])
                ->toArray()
        );
    }
}

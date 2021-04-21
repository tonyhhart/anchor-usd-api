<?php

namespace App\Models;

use App\Traits\HasTypedFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function PHPUnit\Framework\isNan;

/**
 * App\Models\Coin
 *
 * @property int $id
 * @property string $name
 * @property string $symbol
 * @property string $coinname
 * @property string $fullname
 * @property string $description
 * @property string|null $image
 * @property float|null $usd_price
 * @property float|null $usd_change_pct_day
 * @property float|null $usd_change_pct_24_hours
 * @property float|null $usd_change_pct_hour
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $image_url
 * @method static \Illuminate\Database\Eloquent\Builder|Coin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coin newQuery()
 * @method static \Illuminate\Database\Query\Builder|Coin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Coin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereCoinname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereUsdChangePct24Hours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereUsdChangePctDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereUsdChangePctHour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coin whereUsdPrice($value)
 * @method static \Illuminate\Database\Query\Builder|Coin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Coin withoutTrashed()
 * @mixin \Eloquent
 */
class Coin extends Model
{
    use HasFactory, HasTypedFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'coinname',
        'fullname',
        'description',
        'image',
        'usd_price',
        'usd_change_pct_day',
        'usd_change_pct_24_hours',
        'usd_change_pct_hour',
    ];

    protected $casts = [
        'usd_price' => 'float',
        'usd_change_pct_day' => 'float',
        'usd_change_pct_24_hours' => 'float',
        'usd_change_pct_hour' => 'float',
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
        $data = array_change_key_case($data, CASE_LOWER);

        if (!in_array($data['symbol'], config('app.available_coins', []), true)) {
            return null;
        }

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
                    'symbol',
                    'coinname',
                    'fullname',
                    'description',
                    'image',
                ])
                ->toArray()
        );
    }
}

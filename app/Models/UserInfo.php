<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInfo
 *
 * @property int $id
 * @property int $user_id
 * @property array|null $coins_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereCoinsOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserInfo whereUserId($value)
 * @mixin \Eloquent
 */
class UserInfo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coins_order',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'coins_order' => 'json',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

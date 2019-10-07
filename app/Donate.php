<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Donate
 *
 * @property int $id
 * @property int $transaction_id
 * @property int $vk_user_id
 * @property string $paid_at
 * @property int $value
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @method static Builder|Donate newModelQuery()
 * @method static Builder|Donate newQuery()
 * @method static Builder|Donate query()
 * @method static Builder|Donate whereCreatedAt($value)
 * @method static Builder|Donate whereId($value)
 * @method static Builder|Donate wherePaidAt($value)
 * @method static Builder|Donate whereTransactionId($value)
 * @method static Builder|Donate whereUpdatedAt($value)
 * @method static Builder|Donate whereUserId($value)
 * @method static Builder|Donate whereValue($value)
 * @method static Builder|Donate whereVkUserId($value)
 * @mixin Eloquent
 */
class Donate extends Model
{
    protected $fillable = [
        'transaction_id',
        'vk_user_id',
        'paid_at',
        'value'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

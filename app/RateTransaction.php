<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\RateTransaction
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $linkable_type
 * @property int|null $linkable_id
 * @property int $value
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $linkable
 * @method static Builder|RateTransaction newModelQuery()
 * @method static Builder|RateTransaction newQuery()
 * @method static Builder|RateTransaction query()
 * @method static Builder|RateTransaction whereCreatedAt($value)
 * @method static Builder|RateTransaction whereId($value)
 * @method static Builder|RateTransaction whereLinkableId($value)
 * @method static Builder|RateTransaction whereLinkableType($value)
 * @method static Builder|RateTransaction whereType($value)
 * @method static Builder|RateTransaction whereUpdatedAt($value)
 * @method static Builder|RateTransaction whereUserId($value)
 * @method static Builder|RateTransaction whereValue($value)
 * @mixin Eloquent
 */
class RateTransaction extends Model
{

    const IN = 1;
    const OUT = 0;

    protected $fillable = [
        'type',
        'value'
    ];

    public function linkable()
    {
        return $this->morphTo();
    }
}

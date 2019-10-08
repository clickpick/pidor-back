<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\PidorFeedback
 *
 * @property int $id
 * @property int $acceptor_id
 * @property int $sender_id
 * @property int $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PidorFeedback newModelQuery()
 * @method static Builder|PidorFeedback newQuery()
 * @method static Builder|PidorFeedback query()
 * @method static Builder|PidorFeedback whereAcceptorId($value)
 * @method static Builder|PidorFeedback whereCreatedAt($value)
 * @method static Builder|PidorFeedback whereId($value)
 * @method static Builder|PidorFeedback whereSenderId($value)
 * @method static Builder|PidorFeedback whereUpdatedAt($value)
 * @method static Builder|PidorFeedback whereValue($value)
 * @mixin Eloquent
 */
class PidorFeedback extends Model
{
    const VALUE = 10;

    protected $fillable = [
        'sender_id',
        'acceptor_id',
        'value'
    ];
}

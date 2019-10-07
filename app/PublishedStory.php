<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\PublishedStory
 *
 * @method static Builder|PublishedStory newModelQuery()
 * @method static Builder|PublishedStory newQuery()
 * @method static Builder|PublishedStory query()
 * @mixin Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|PublishedStory whereCreatedAt($value)
 * @method static Builder|PublishedStory whereId($value)
 * @method static Builder|PublishedStory whereType($value)
 * @method static Builder|PublishedStory whereUpdatedAt($value)
 * @method static Builder|PublishedStory whereUserId($value)
 * @property-read User $user
 */
class PublishedStory extends Model
{

    const CONFESSION = 'confession';
    const CONFESSION_REWARD_RATE = 20;

    protected $fillable = [
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function payReward()
    {
        switch ($this->type) {
            case self::CONFESSION:
                $rate = self::CONFESSION_REWARD_RATE;
                break;

            default:
                $rate = 0;
        }

        $this->user->minusRate($rate, $this);
    }
}

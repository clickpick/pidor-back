<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\PidorOfTheDay
 *
 * @property int $id
 * @property Carbon $pidor_at
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|PidorOfTheDay newModelQuery()
 * @method static Builder|PidorOfTheDay newQuery()
 * @method static Builder|PidorOfTheDay query()
 * @method static Builder|PidorOfTheDay whereCreatedAt($value)
 * @method static Builder|PidorOfTheDay whereId($value)
 * @method static Builder|PidorOfTheDay wherePidorAt($value)
 * @method static Builder|PidorOfTheDay whereUpdatedAt($value)
 * @method static Builder|PidorOfTheDay whereUserId($value)
 * @mixin Eloquent
 */
class PidorOfTheDay extends Model
{
    protected $fillable = [
        'pidor_at',
        'user_id'
    ];

    protected $casts = [
        'pidor_at' => 'date'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function getCurrent() {
        $pidorOfTheDay = self::wherePidorAt(Carbon::now())->first();

        if ($pidorOfTheDay) {
            $user = $pidorOfTheDay->user;
        } else {
            $user = User::inRandomOrder()->first();

            self::create([
                'user_id' => $user->id,
                'pidor_at' => Carbon::now()
            ]);
        }

        return $user;
    }
}

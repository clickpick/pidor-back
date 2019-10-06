<?php

namespace App;

use App\Events\UserBecamePidor;
use App\Events\UserCreated;
use App\Services\Facades\Giphy;
use App\Services\VkClient;
use Eloquent;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Regex\Regex;


/**
 * App\User
 *
 * @property int $id
 * @property int $vk_user_id
 * @property bool $notifications_are_enabled
 * @property bool $messages_are_enabled
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $avatar_200
 * @property string|null $bdate
 * @property int $sex
 * @property int|null $utc_offset
 * @property Carbon|null $visited_at
 * @property int $pidor_rate
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar200($value)
 * @method static Builder|User whereBdate($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereMessagesAreEnabled($value)
 * @method static Builder|User whereNotificationsAreEnabled($value)
 * @method static Builder|User wherePidorRate($value)
 * @method static Builder|User whereSex($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUtcOffset($value)
 * @method static Builder|User whereVisitedAt($value)
 * @method static Builder|User whereVkUserId($value)
 * @mixin Eloquent
 * @property string|null $gif
 * @method static Builder|User whereGif($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    const PIDOR_CHANCE = 10;

    const PHRASES = [
        25 => 'такое',
        50 => 'набираешь',
        75 => 'почти гей',
        100 => 'пидрила'
    ];

    protected $attributes = [
        'pidor_rate' => 0
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vk_user_id',
        'utc_offset',
        'notifications_are_enabled',
        'messages_are_enabled',
        'visited_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'notifications_are_enabled' => 'boolean',
        'messages_are_enabled' => 'boolean',
        'visited_at' => 'date',
        'is_pidor' => 'boolean'
    ];


    protected $dispatchesEvents = [
        'created' => UserCreated::class
    ];


    public function fillPersonalInfoFromVk($data = null) {
        $data = $data ?? (new VkClient())->getUsers($this->vk_user_id, ['first_name', 'last_name', 'photo_200', 'timezone', 'sex', 'bdate']);

        $this->first_name = $data['first_name'] ?? null;
        $this->last_name = $data['last_name'] ?? null;
        $this->avatar_200 = $data['photo_200'] ?? null;
        $this->sex = $data['sex'] ?? 0;


        if (isset($data['bdate'])) {
            $reYear = Regex::match('/\d{1,2}.\d{1,2}.\d{4}/', $data['bdate']);
            $reDay = Regex::match('/\d{1,2}.\d{1,2}/', $data['bdate']);

            if ($reYear->hasMatch()) {
                $this->bdate = Carbon::parse($data['bdate']);
            } elseif ($reDay->hasMatch()) {

                $date = explode('.', $data['bdate']);

                $bdate = new Carbon();

                $bdate->setYear(1);
                $bdate->setMonth($date[1]);
                $bdate->setDay($date[0]);

                $this->bdate = $bdate;

            } else {
                $this->bdate = null;
            }
        }

        if (isset($data['timezone'])) {
            $this->utc_offset = $data['timezone'] * 60;
        }

        $this->save();
    }

    /**
     * @param $vkId
     * @return User
     */
    public static function getByVkId($vkId) : ?self {

        if (!$vkId) {
            return null;
        }

        return self::firstOrCreate(['vk_user_id' => $vkId]);
    }


    public function testPidor() {
        $this->pidor_rate = rand(1, 100);
        $this->save();

        if ($this->pidor_rate === 100) {
            event(new UserBecamePidor($this));
        }
    }

    public function updateGif() {
        try {
            $mp4Gif = Giphy::random([
                'tag' => 'gay',
                'rating' => 'R'
            ])['data']['image_mp4_url'];
        } catch (Exception $e) {
            $mp4Gif = null;
        }

        $this->gif = $mp4Gif;
        $this->save();
    }

    private function getPhrases() {
        return collect(self::PHRASES);
    }

    public function getPhrase() {
        return $this->getPhrases()->first(function($value, $key) {
            return $this->pidor_rate < $key;
        });
    }
}

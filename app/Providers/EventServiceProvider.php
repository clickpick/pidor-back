<?php

namespace App\Providers;

use App\Events\StoryPosted;
use App\Events\UserBecamePidor;
use App\Events\UserCreated;
use App\Listeners\AttachStoryPosted;
use App\Listeners\CheckUserForPidor;
use App\Listeners\FillPersonalDataFromVk;
use App\Listeners\RewardForStoryPosted;
use App\Listeners\SetGif;
use App\Listeners\WritePidorInLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserCreated::class => [
            CheckUserForPidor::class,
            SetGif::class,
            FillPersonalDataFromVk::class,
        ],
        StoryPosted::class => [
            RewardForStoryPosted::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

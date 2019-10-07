<?php

namespace App\Providers;

use App\Services\VkDonate;
use Illuminate\Support\ServiceProvider;

class VkDonateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('vkDonate', function ($app)
        {
            return new VkDonate(config('services.vk_donate.key'));
        });
        $this->app->alias('vkDonate', VkDonate::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

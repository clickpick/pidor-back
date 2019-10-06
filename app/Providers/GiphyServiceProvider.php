<?php

namespace App\Providers;

use App\Services\Giphy;
use Illuminate\Support\ServiceProvider;

class GiphyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('giphy', function ($app)
        {
            return new Giphy(config('services.giphy.key'));
        });
        $this->app->alias('giphy', Giphy::class);
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

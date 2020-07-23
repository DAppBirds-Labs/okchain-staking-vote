<?php

namespace App\Providers;

use App\Providers\Impl\Online;
use Illuminate\Support\ServiceProvider;

class OnlineServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('online', function ($app) {
            return new Online();
        });
    }


}

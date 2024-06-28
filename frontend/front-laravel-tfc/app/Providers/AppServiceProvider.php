<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (env('APP_ENV') === 'production' || env('APP_ENV') === 'local') {
            URL::forceScheme('https');
        }
    }

    public function register()
    {
        //
    }
}


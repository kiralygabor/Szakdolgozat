<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\MaskEmailMixin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
            URL::forceScheme('https');
        }

        Str::mixin(new MaskEmailMixin());

        \App\Models\Advertisement::observe(\App\Observers\AdvertisementObserver::class);
    }
}

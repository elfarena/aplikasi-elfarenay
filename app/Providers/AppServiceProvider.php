<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS saat berjalan di production (Railway, dll)
        // Ini mencegah error Mixed Content di browser
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}

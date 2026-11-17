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
        // Vercel terminates TLS at its edge. Force generated URLs (Filament
        // assets, redirects, password links) to use HTTPS in production.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}

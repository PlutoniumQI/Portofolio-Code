<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\TestimonialSubmission;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // Vercel terminates TLS at its edge. Force generated URLs (redirects,
        // assets) to use HTTPS in production.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        View::composer('admin.layout', function ($view): void {
            $view->with([
                'navPendingTestimonials' => TestimonialSubmission::query()
                    ->where('status', TestimonialSubmission::STATUS_PENDING)
                    ->count(),
                'navUnreadMessages' => Message::query()
                    ->where('is_read', false)
                    ->count(),
            ]);
        });
    }
}

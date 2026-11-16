<?php

namespace App\Providers;

use App\Filesystem\GitHubAdapter;
use Illuminate\Filesystem\FilesystemAdapter as LaravelFilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

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
        // Render serves the app over HTTPS through its proxy. Force generated
        // URLs (Filament assets, redirects, password links) to use HTTPS.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Storage::extend('github', function ($app, array $config): LaravelFilesystemAdapter {
            $adapter = new GitHubAdapter(
                token: (string) ($config['token'] ?? ''),
                owner: (string) ($config['owner'] ?? ''),
                repo: (string) ($config['repo'] ?? ''),
                branch: (string) ($config['branch'] ?? 'main'),
                root: (string) ($config['root'] ?? ''),
                apiUrl: (string) ($config['api_url'] ?? 'https://api.github.com'),
                publicUrl: (string) ($config['url'] ?? ''),
            );

            return new LaravelFilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config,
            );
        });
    }
}

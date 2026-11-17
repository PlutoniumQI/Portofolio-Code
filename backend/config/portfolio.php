<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Legacy static export
    |--------------------------------------------------------------------------
    |
    | The frontend no longer reads src/data/portfolio.json. Keep these paths so
    | the retired export command still runs; do not add new features to it.
    |
    */

    'export_json' => env('PORTFOLIO_EXPORT_JSON', base_path('../src/data/portfolio.json')),

    'export_images' => env('PORTFOLIO_EXPORT_IMAGES', base_path('../public/images/projects')),

    /*
    |--------------------------------------------------------------------------
    | Offline fallback
    |--------------------------------------------------------------------------
    |
    | The Next.js site uses this JSON when the API is unreachable (for example
    | during a Vercel build). Regenerate it with `php artisan portfolio:fallback`
    | after content changes.
    |
    */

    'fallback_json' => env('PORTFOLIO_FALLBACK_JSON', base_path('../src/data/portfolio-fallback.json')),

    /*
    |--------------------------------------------------------------------------
    | Frontend integration
    |--------------------------------------------------------------------------
    |
    | The Next.js site is deployed separately on Vercel. After a content
    | change, the admin calls the revalidation endpoint so ISR pages refresh.
    |
    */

    'frontend' => [
        'url' => env('FRONTEND_URL', 'http://localhost:3000'),
        'revalidate_url' => env('FRONTEND_REVALIDATE_URL'),
        'revalidate_secret' => env('FRONTEND_REVALIDATE_SECRET'),
    ],

];

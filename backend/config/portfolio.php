<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload limit
    |--------------------------------------------------------------------------
    |
    | Maximum size (in kilobytes) accepted by the Filament image upload field.
    | Keep this at or below the local PHP `upload_max_filesize` setting, or
    | PHP will reject the upload before Filament can validate it.
    |
    */

    'max_upload_kb' => (int) env('PORTFOLIO_MAX_UPLOAD_KB', 2048),

    /*
    |--------------------------------------------------------------------------
    | Legacy static export
    |--------------------------------------------------------------------------
    |
    | Kept while the frontend still reads src/data/portfolio.json. Phase 2
    | replaces this pipeline with GET /api/portfolio and the revalidation
    | webhook below. Do not add new features to the export path.
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
    | the Render free instance is asleep during a build). Regenerate it with
    | `php artisan portfolio:fallback` after content changes.
    |
    */

    'fallback_json' => env('PORTFOLIO_FALLBACK_JSON', base_path('../src/data/portfolio-fallback.json')),

    /*
    |--------------------------------------------------------------------------
    | GitHub assets
    |--------------------------------------------------------------------------
    |
    | Production uploads are committed to a public GitHub repository so they
    | survive Render redeploys. Use a fine-grained token with Contents:
    | Read and write access to that single repository. When the token is empty
    | (local development), uploads fall back to the public disk.
    |
    */

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'owner' => env('GITHUB_ASSETS_OWNER'),
        'repo' => env('GITHUB_ASSETS_REPO'),
        'branch' => env('GITHUB_ASSETS_BRANCH', 'main'),
        'path' => trim((string) env('GITHUB_ASSETS_PATH', 'portfolio'), '/'),
        'api_url' => env('GITHUB_API_URL', 'https://api.github.com'),
    ],

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

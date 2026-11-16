<?php

$allowedOrigins = array_values(array_filter(array_map(
    static fn (string $origin): string => trim($origin),
    explode(',', (string) env(
        'FRONTEND_ALLOWED_ORIGINS',
        'http://localhost:3000,http://127.0.0.1:3000',
    )),
)));

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | The Next.js site calls the public API from the browser for the contact
    | form. Only the origins listed in FRONTEND_ALLOWED_ORIGINS may do so.
    | Vercel preview URLs can be enabled temporarily during frontend work.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'OPTIONS'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => filter_var(
        env('FRONTEND_ALLOW_VERCEL_PREVIEWS', false),
        FILTER_VALIDATE_BOOLEAN,
    ) ? ['#^https://[a-z0-9-]+\.vercel\.app$#'] : [],

    'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'Origin', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 3600,

    'supports_credentials' => false,

];

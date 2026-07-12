<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            // On production (Vercel), we switch the public disk driver to 's3' (pointing to Supabase).
            // This prevents your existing code/Filament pages referencing 'public' from failing.
            'driver' => env('PUBLIC_DISK_DRIVER', 'local'),
            'root' => storage_path('app/public'),
            'url' => env('PUBLIC_DISK_URL', env('APP_URL').'/storage'),
            'visibility' => 'public',
            'throw' => false,
            // S3 / Supabase overrides:
            'key' => env('SUPABASE_ACCESS_KEY_ID'),
            'secret' => env('SUPABASE_SECRET_ACCESS_KEY'),
            'region' => env('SUPABASE_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('SUPABASE_BUCKET'),
            'endpoint' => env('SUPABASE_ENDPOINT'),
            'use_path_style_endpoint' => env('SUPABASE_USE_PATH_STYLE_ENDPOINT', true),
        ],

        // Dedicated Supabase disk (alternative usage)
        'supabase' => [
            'driver' => 's3',
            'key' => env('SUPABASE_ACCESS_KEY_ID'),
            'secret' => env('SUPABASE_SECRET_ACCESS_KEY'),
            'region' => env('SUPABASE_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('SUPABASE_BUCKET'),
            'endpoint' => env('SUPABASE_ENDPOINT'),
            'use_path_style_endpoint' => env('SUPABASE_USE_PATH_STYLE_ENDPOINT', true),
            'url' => env('SUPABASE_PUBLIC_URL'),
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];

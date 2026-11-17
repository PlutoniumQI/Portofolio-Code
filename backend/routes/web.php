<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'service' => 'portfolio-api',
    'status' => 'ok',
    'health' => url('/api/health'),
    'admin' => url('/admin'),
]));

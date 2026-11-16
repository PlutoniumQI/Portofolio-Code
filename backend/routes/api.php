<?php

use App\Http\Controllers\Api\HealthController;
use Illuminate\Support\Facades\Route;

// Public, read-only API consumed by the Next.js frontend. Content routes
// (portfolio, projects, messages) are added in phase 2.
Route::get('/health', HealthController::class)->name('api.health');

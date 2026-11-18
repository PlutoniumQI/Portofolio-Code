<?php

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TestimonialSubmissionController;
use Illuminate\Support\Facades\Route;

// Public, read-only content API consumed by the Next.js frontend.
Route::get('/health', HealthController::class)->name('api.health');
Route::get('/portfolio', PortfolioController::class)->name('api.portfolio');

Route::get('/projects', [ProjectController::class, 'index'])->name('api.projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('api.projects.show');

Route::get('/posts', [PostController::class, 'index'])->name('api.posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('api.posts.show');

// Contact messages and testimonial submissions. The throttle is per client IP.
Route::post('/messages', [MessageController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('api.messages.store');

Route::post('/testimonials', [TestimonialSubmissionController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('api.testimonials.store');

<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublishController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\TestimonialSubmissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'service' => 'portfolio-api',
    'status' => 'ok',
    'health' => url('/api/health'),
    'admin' => url('/admin'),
]));

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('site-profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('site-profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('profile', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('profile', [AccountController::class, 'update'])->name('account.update');

        Route::get('publish', [PublishController::class, 'index'])->name('publish.index');
        Route::post('publish', [PublishController::class, 'publish'])->name('publish');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::put('messages/{message}', [MessageController::class, 'update'])->name('messages.update');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('testimonial-submissions', [TestimonialSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('testimonial-submissions/{submission}', [TestimonialSubmissionController::class, 'show'])->name('submissions.show');
        Route::post('testimonial-submissions/{submission}/publish', [TestimonialSubmissionController::class, 'publish'])->name('submissions.publish');
        Route::post('testimonial-submissions/{submission}/reject', [TestimonialSubmissionController::class, 'reject'])->name('submissions.reject');
        Route::delete('testimonial-submissions/{submission}', [TestimonialSubmissionController::class, 'destroy'])->name('submissions.destroy');

        foreach (config('admin.resources') as $slug => $definition) {
            Route::get($slug, [ResourceController::class, 'index'])
                ->defaults('resource', $slug)
                ->name("{$slug}.index");
            Route::get("{$slug}/create", [ResourceController::class, 'create'])
                ->defaults('resource', $slug)
                ->name("{$slug}.create");
            Route::post($slug, [ResourceController::class, 'store'])
                ->defaults('resource', $slug)
                ->name("{$slug}.store");
            Route::post("{$slug}/reorder", [ResourceController::class, 'reorder'])
                ->defaults('resource', $slug)
                ->name("{$slug}.reorder");
            Route::get("{$slug}/{record}/edit", [ResourceController::class, 'edit'])
                ->defaults('resource', $slug)
                ->name("{$slug}.edit");
            Route::put("{$slug}/{record}", [ResourceController::class, 'update'])
                ->defaults('resource', $slug)
                ->name("{$slug}.update");
            Route::delete("{$slug}/{record}", [ResourceController::class, 'destroy'])
                ->defaults('resource', $slug)
                ->name("{$slug}.destroy");
        }
    });
});

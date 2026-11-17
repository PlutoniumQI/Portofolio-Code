<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class HealthController extends Controller
{
    /**
     * Lightweight liveness endpoint for the frontend, uptime monitors, and
     * post-deploy smoke tests.
     *
     * It intentionally does not touch the database so it stays fast even while
     * Neon is waking up. Laravel exposes /up separately.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'portfolio-api',
            'environment' => app()->environment(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

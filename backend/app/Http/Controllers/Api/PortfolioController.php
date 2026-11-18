<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function __invoke(Request $request, PortfolioService $portfolio): JsonResponse
    {
        $locale = $request->query('locale') === 'en' ? 'en' : 'id';

        // Next.js owns the public cache (ISR + tag revalidation). Do not add a
        // shared max-age here: Vercel would serve a stale API payload after the
        // admin saves, even when the revalidation webhook has run.
        return response()->json($portfolio->payload($locale));
    }
}

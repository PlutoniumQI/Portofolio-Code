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

        return response()
            ->json($portfolio->payload($locale))
            ->header('Cache-Control', 'public, max-age=60, s-maxage=300');
    }
}

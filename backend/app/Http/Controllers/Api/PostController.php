<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, PortfolioService $portfolio): JsonResponse
    {
        $locale = $request->query('locale') === 'en' ? 'en' : 'id';
        $query = Post::query()->published();

        if (filled($search = trim((string) $request->query('q')))) {
            $query->where(function (Builder $builder) use ($search): void {
                $like = '%'.$search.'%';

                $builder
                    ->where('title_id', 'like', $like)
                    ->orWhere('title_en', 'like', $like)
                    ->orWhere('excerpt_id', 'like', $like)
                    ->orWhere('excerpt_en', 'like', $like);
            });
        }

        if (filled($category = $request->query('category'))) {
            $query->where('category', $category);
        }

        $posts = $query->orderByDesc('published_at')->get();

        return response()->json([
            'locale' => $locale,
            'data' => $posts
                ->map(fn (Post $post): array => $portfolio->post($post, $locale, full: false))
                ->all(),
            'meta' => [
                'total' => $posts->count(),
            ],
        ]);
    }

    public function show(string $slug, Request $request, PortfolioService $portfolio): JsonResponse
    {
        $locale = $request->query('locale') === 'en' ? 'en' : 'id';

        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'locale' => $locale,
            'data' => $portfolio->post($post, $locale),
        ]);
    }
}

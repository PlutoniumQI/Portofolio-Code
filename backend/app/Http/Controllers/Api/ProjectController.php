<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\PortfolioService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request, PortfolioService $portfolio): JsonResponse
    {
        $locale = $request->query('locale') === 'en' ? 'en' : 'id';
        $query = Project::query()->visible();

        if (filled($search = trim((string) $request->query('q')))) {
            $query->where(function (Builder $builder) use ($search): void {
                $like = '%'.$search.'%';

                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('title_en', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('description_en', 'like', $like)
                    ->orWhere('excerpt_id', 'like', $like)
                    ->orWhere('excerpt_en', 'like', $like);
            });
        }

        if (filled($category = $request->query('category'))) {
            $query->where('category', $category);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $projects = $query
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'locale' => $locale,
            'data' => $projects
                ->map(fn (Project $project): array => $portfolio->project($project, $locale, full: false))
                ->all(),
            'meta' => [
                'total' => $projects->count(),
                'categories' => collect(Project::categories())
                    ->map(fn (string $label, string $value): array => [
                        'value' => $value,
                        'label' => $label,
                    ])
                    ->values()
                    ->all(),
            ],
        ]);
    }

    public function show(string $slug, Request $request, PortfolioService $portfolio): JsonResponse
    {
        $locale = $request->query('locale') === 'en' ? 'en' : 'id';

        $project = Project::query()
            ->visible()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'locale' => $locale,
            'data' => $portfolio->project($project, $locale),
        ]);
    }
}

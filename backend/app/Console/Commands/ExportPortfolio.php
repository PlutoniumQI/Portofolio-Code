<?php

namespace App\Console\Commands;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExportPortfolio extends Command
{
    protected $signature = 'portfolio:export';

    protected $description = 'Export published portfolio content and uploaded images for the static Next.js site';

    public function handle(): int
    {
        $jsonPath = config('portfolio.export_json');
        $imagesPath = config('portfolio.export_images');

        File::ensureDirectoryExists(dirname($jsonPath));
        File::ensureDirectoryExists($imagesPath);

        $projects = Project::query()
            ->whereIn('status', [Project::STATUS_PUBLISHED, Project::STATUS_COMING_SOON])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Project $project): array => [
                'id' => (string) $project->id,
                'title' => $project->title,
                'description' => $project->description ?? '',
                'tags' => array_values($project->tags ?? []),
                'url' => $project->url ?? '',
                'imageUrl' => $this->resolveImageUrl($project, $imagesPath),
                'status' => $project->status,
            ])
            ->all();

        $experiences = Experience::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (Experience $experience): array => [
                'id' => (string) $experience->id,
                'dateRange' => $experience->date_range,
                'title' => $experience->title,
                'company' => $experience->company,
                'description' => $experience->description ?? '',
                'tags' => array_values($experience->tags ?? []),
            ])
            ->all();

        $payload = [
            'projects' => $projects,
            'experiences' => $experiences,
        ];

        File::put(
            $jsonPath,
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL,
        );

        $this->info(sprintf(
            'Exported %d project(s) and %d experience(s) to %s',
            count($projects),
            count($experiences),
            $jsonPath,
        ));
        $this->line('Local images copied to '.$imagesPath);

        return self::SUCCESS;
    }

    /**
     * Resolve the public image URL, copying a locally uploaded file into the
     * frontend's public directory when one exists. External URLs (for example
     * GitHub raw links) are passed through untouched.
     */
    private function resolveImageUrl(Project $project, string $imagesPath): string
    {
        if (filled($project->image_path)) {
            $disk = Storage::disk('public');

            if ($disk->exists($project->image_path)) {
                $filename = basename($project->image_path);

                File::copy($disk->path($project->image_path), $imagesPath.DIRECTORY_SEPARATOR.$filename);

                return '/images/projects/'.$filename;
            }

            $this->warn("Missing upload for project [{$project->id}]: {$project->image_path}");
        }

        return $project->image_url ?? '';
    }
}

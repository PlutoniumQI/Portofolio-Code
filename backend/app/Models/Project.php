<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RevalidatesFrontend;
use App\Support\Media;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'title',
    'title_en',
    'category',
    'description',
    'excerpt_id',
    'excerpt_en',
    'description_en',
    'content_id',
    'content_en',
    'tags',
    'url',
    'repo_url',
    'image_path',
    'image_url',
    'image_disk',
    'gallery',
    'status',
    'is_featured',
    'sort_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    use HasTranslations;
    use RevalidatesFrontend;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_COMING_SOON = 'coming_soon';

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_COMING_SOON => 'Coming Soon',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            'web-app' => 'Web app',
            'ui-ux' => 'UI/UX',
            'mobile' => 'Mobile',
            'design-system' => 'Design system',
            'other' => 'Other',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PUBLISHED, self::STATUS_COMING_SOON]);
    }

    /**
     * Get the public image URL. GitHub uploads resolve to a raw GitHub URL;
     * local uploads resolve to /storage on the backend origin.
     */
    public function imageUrl(): ?string
    {
        return Media::url($this->image_path, $this->image_disk, $this->image_url);
    }

    /**
     * @return array<int, string>
     */
    public function galleryUrls(): array
    {
        return collect($this->gallery ?? [])
            ->map(function (mixed $path): ?string {
                // Older uploads stored each row as ['url' => '...']. Seeds and
                // the legacy export use plain strings, so accept both shapes and
                // never let one malformed row break the API.
                if (is_array($path)) {
                    $path = $path['url'] ?? null;
                }

                if (! is_string($path) || blank($path)) {
                    return null;
                }

                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                    return $path;
                }

                return Media::url($path, $this->image_disk);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Legacy static-export resolver. Local uploads are copied into the
     * frontend's public/images/projects directory by the export command.
     */
    public function resolvedImageUrl(): ?string
    {
        if (filled($this->image_path)) {
            return '/images/projects/'.basename($this->image_path);
        }

        return filled($this->image_url) ? $this->image_url : null;
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}

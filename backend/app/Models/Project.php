<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'description',
    'tags',
    'url',
    'image_path',
    'image_url',
    'status',
    'sort_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

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
     * Get the public image path, preferring a locally uploaded file over an external URL.
     */
    public function resolvedImageUrl(): ?string
    {
        if (filled($this->image_path)) {
            return '/images/projects/'.basename($this->image_path);
        }

        return filled($this->image_url) ? $this->image_url : null;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'sort_order' => 'integer',
        ];
    }
}

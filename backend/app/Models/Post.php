<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'title_id',
    'title_en',
    'excerpt_id',
    'excerpt_en',
    'body_id',
    'body_en',
    'cover_path',
    'cover_url',
    'cover_disk',
    'category',
    'tags',
    'status',
    'published_at',
    'is_featured',
])]
class Post extends Model
{
    use HasTranslations;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function coverUrl(): ?string
    {
        return Media::url($this->cover_path, $this->cover_disk, $this->cover_url);
    }

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
        ];
    }
}

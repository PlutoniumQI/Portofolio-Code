<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RevalidatesFrontend;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'role_id',
    'role_en',
    'company',
    'quote_id',
    'quote_en',
    'category',
    'avatar_path',
    'avatar_url',
    'avatar_disk',
    'rating',
    'sort_order',
    'is_active',
])]
class Testimonial extends Model
{
    use HasTranslations;
    use RevalidatesFrontend;

    public const CATEGORY_CLIENT = 'client';

    public const CATEGORY_MENTOR = 'mentor';

    public const CATEGORY_TEACHER = 'teacher';

    public const CATEGORY_PEER = 'peer';

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_CLIENT => 'Client',
            self::CATEGORY_MENTOR => 'Mentor',
            self::CATEGORY_TEACHER => 'Teacher',
            self::CATEGORY_PEER => 'Peer',
        ];
    }

    public function avatarUrl(): ?string
    {
        return Media::url($this->avatar_path, $this->avatar_disk, $this->avatar_url);
    }

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

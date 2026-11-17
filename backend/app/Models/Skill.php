<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'category',
    'level',
    'description_id',
    'description_en',
    'icon',
    'sort_order',
    'is_active',
])]
class Skill extends Model
{
    use HasTranslations;

    public const CATEGORY_HARD = 'hard';

    public const CATEGORY_SOFT = 'soft';

    public const LEVEL_BEGINNER = 'beginner';

    public const LEVEL_INTERMEDIATE = 'intermediate';

    public const LEVEL_ADVANCED = 'advanced';

    public const LEVEL_EXPERT = 'expert';

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_HARD => 'Hard skill',
            self::CATEGORY_SOFT => 'Soft skill',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function levels(): array
    {
        return [
            self::LEVEL_BEGINNER => 'Beginner',
            self::LEVEL_INTERMEDIATE => 'Intermediate',
            self::LEVEL_ADVANCED => 'Advanced',
            self::LEVEL_EXPERT => 'Expert',
        ];
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

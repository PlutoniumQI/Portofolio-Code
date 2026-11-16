<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'category',
    'percentage',
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

    protected function casts(): array
    {
        return [
            'percentage' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\ExperienceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'type',
    'title',
    'title_en',
    'company',
    'location',
    'date_range',
    'description',
    'description_en',
    'tags',
    'url',
    'sort_order',
    'is_active',
])]
class Experience extends Model
{
    /** @use HasFactory<ExperienceFactory> */
    use HasFactory;

    use HasTranslations;

    public const TYPE_WORK = 'work';

    public const TYPE_ORGANIZATION = 'organization';

    public const TYPE_COMPETITION = 'competition';

    public const TYPE_INTERNSHIP = 'internship';

    public const TYPE_EDUCATION = 'education';

    /**
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            self::TYPE_WORK => 'Work',
            self::TYPE_ORGANIZATION => 'Organisation',
            self::TYPE_COMPETITION => 'Competition',
            self::TYPE_INTERNSHIP => 'PLK / Internship',
            self::TYPE_EDUCATION => 'Education',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Models\Concerns\RevalidatesFrontend;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'institution',
    'degree_id',
    'degree_en',
    'field_id',
    'field_en',
    'start_date',
    'end_date',
    'description_id',
    'description_en',
    'sort_order',
    'is_active',
])]
class Education extends Model
{
    use HasTranslations;
    use RevalidatesFrontend;

    /**
     * Laravel's inflector treats "education" as uncountable, so the table has
     * to be named explicitly.
     */
    protected $table = 'educations';

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

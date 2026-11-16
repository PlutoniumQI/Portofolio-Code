<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title_id',
    'title_en',
    'issuer',
    'issued_at',
    'expires_at',
    'credential_id',
    'credential_url',
    'image_path',
    'image_url',
    'image_disk',
    'description_id',
    'description_en',
    'sort_order',
    'is_active',
])]
class Certificate extends Model
{
    use HasTranslations;

    public function imageUrl(): ?string
    {
        return Media::url($this->image_path, $this->image_disk, $this->image_url);
    }

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'expires_at' => 'date',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'full_name',
    'headline_id',
    'headline_en',
    'intro_id',
    'intro_en',
    'about_id',
    'about_en',
    'location',
    'email',
    'phone',
    'availability_id',
    'availability_en',
    'avatar_path',
    'avatar_url',
    'avatar_disk',
    'cv_path',
    'cv_url',
    'cv_disk',
    'linkedin_url',
    'github_url',
    'instagram_url',
    'website_url',
    'years_learning',
    'tools_mastered',
    'is_available',
])]
class Profile extends Model
{
    use HasTranslations;

    public static function current(): self
    {
        return self::query()->firstOrCreate([], [
            'full_name' => 'Muhammad Resky Ramadhan',
            'headline_id' => 'Siswa PPLG & Desainer UI/UX',
            'headline_en' => 'Software Engineering Student & UI/UX Designer',
            'location' => 'Banjarmasin, Indonesia',
            'email' => 'hello@example.com',
            'linkedin_url' => 'https://www.linkedin.com/',
            'github_url' => 'https://github.com/PlutoniumQI',
            'years_learning' => 1,
            'tools_mastered' => 5,
            'is_available' => true,
        ]);
    }

    public function avatarUrl(): ?string
    {
        return Media::url($this->avatar_path, $this->avatar_disk, $this->avatar_url);
    }

    public function cvUrl(): ?string
    {
        return Media::url($this->cv_path, $this->cv_disk, $this->cv_url);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'years_learning' => 'integer',
            'tools_mastered' => 'integer',
            'is_available' => 'boolean',
        ];
    }
}

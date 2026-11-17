<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PortfolioService
{
    /**
     * Full payload consumed by the Next.js site. Everything is already
     * localized, so the frontend never needs to know which database column
     * holds the Indonesian or English value.
     *
     * @return array<string, mixed>
     */
    public function payload(string $locale = 'id'): array
    {
        $profile = Profile::current();
        $projects = Project::query()
            ->visible()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $skills = Skill::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return [
            'locale' => $locale,
            'generated_at' => now()->toIso8601String(),
            'profile' => $this->profile($profile, $locale, $projects->count()),
            'skills' => [
                'hard' => $skills
                    ->where('category', Skill::CATEGORY_HARD)
                    ->map(fn (Skill $skill): array => $this->skill($skill, $locale))
                    ->values()
                    ->all(),
                'soft' => $skills
                    ->where('category', Skill::CATEGORY_SOFT)
                    ->map(fn (Skill $skill): array => $this->skill($skill, $locale))
                    ->values()
                    ->all(),
            ],
            'educations' => Education::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (Education $education): array => $this->education($education, $locale))
                ->all(),
            'certificates' => Certificate::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderByDesc('issued_at')
                ->get()
                ->map(fn (Certificate $certificate): array => $this->certificate($certificate, $locale))
                ->all(),
            'experiences' => Experience::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (Experience $experience): array => $this->experience($experience, $locale))
                ->all(),
            'projects' => $projects
                ->map(fn (Project $project): array => $this->project($project, $locale))
                ->all(),
            'project_categories' => $this->projectCategories($projects),
            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (Testimonial $testimonial): array => $this->testimonial($testimonial, $locale))
                ->all(),
            'testimonial_categories' => $this->testimonialCategories(),
            'posts' => Post::query()
                ->published()
                ->orderByDesc('published_at')
                ->get()
                ->map(fn (Post $post): array => $this->post($post, $locale))
                ->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function profile(Profile $profile, string $locale, int $projectCount): array
    {
        return [
            'id' => (string) $profile->id,
            'full_name' => $profile->full_name,
            'headline' => $profile->translated('headline', $locale),
            'intro' => $profile->translated('intro', $locale),
            'about' => $profile->translated('about', $locale),
            'location' => $profile->location,
            'email' => $profile->email,
            'phone' => $profile->phone,
            'availability' => $profile->translated('availability', $locale),
            'is_available' => $profile->is_available,
            'avatar_url' => $profile->avatarUrl(),
            'cv_url' => $profile->cvUrl(),
            'socials' => array_filter([
                'linkedin' => $profile->linkedin_url,
                'github' => $profile->github_url,
                'instagram' => $profile->instagram_url,
                'website' => $profile->website_url,
            ]),
            'stats' => [
                'years_learning' => $profile->years_learning,
                'projects_completed' => $projectCount,
                'tools_mastered' => $profile->tools_mastered,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function skill(Skill $skill, string $locale): array
    {
        return [
            'id' => (string) $skill->id,
            'name' => $skill->name,
            'category' => $skill->category,
            'level' => $skill->level,
            'description' => $skill->translated('description', $locale),
            'icon' => $skill->icon,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function education(Education $education, string $locale): array
    {
        return [
            'id' => (string) $education->id,
            'institution' => $education->institution,
            'degree' => $education->translated('degree', $locale),
            'field' => $education->translated('field', $locale),
            'start_date' => $education->start_date,
            'end_date' => $education->end_date,
            'period' => trim($education->start_date.' — '.($education->end_date ?: 'Now'), ' —'),
            'description' => $education->translated('description', $locale),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function certificate(Certificate $certificate, string $locale): array
    {
        return [
            'id' => (string) $certificate->id,
            'title' => $certificate->translated('title', $locale),
            'issuer' => $certificate->issuer,
            'issued_at' => $certificate->issued_at?->toDateString(),
            'expires_at' => $certificate->expires_at?->toDateString(),
            'credential_id' => $certificate->credential_id,
            'credential_url' => $certificate->credential_url,
            'image_url' => $certificate->imageUrl(),
            'description' => $certificate->translated('description', $locale),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function experience(Experience $experience, string $locale): array
    {
        return [
            'id' => (string) $experience->id,
            'type' => $experience->type,
            'type_label' => Experience::types()[$experience->type] ?? $experience->type,
            'title' => $experience->translated('title', $locale),
            'company' => $experience->company,
            'location' => $experience->location,
            'date_range' => $experience->date_range,
            'description' => $experience->translated('description', $locale),
            'tags' => array_values($experience->tags ?? []),
            'url' => $experience->url,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function project(Project $project, string $locale, bool $full = true): array
    {
        $data = [
            'id' => (string) $project->id,
            'slug' => $project->slug ?: Str::slug($project->title).'-'.$project->id,
            'title' => $project->translated('title', $locale),
            'excerpt' => $project->translated('excerpt', $locale) ?: $project->translated('description', $locale),
            'description' => $project->translated('description', $locale),
            'category' => $project->category,
            'category_label' => Project::categories()[$project->category] ?? $project->category,
            'tags' => array_values($project->tags ?? []),
            'url' => $project->url,
            'repo_url' => $project->repo_url,
            'image_url' => $project->imageUrl(),
            'status' => $project->status,
            'is_featured' => $project->is_featured,
        ];

        if ($full) {
            $data['content'] = $project->translated('content', $locale);
            $data['gallery'] = $project->galleryUrls();
            $data['created_at'] = $project->created_at?->toIso8601String();
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function testimonial(Testimonial $testimonial, string $locale): array
    {
        return [
            'id' => (string) $testimonial->id,
            'name' => $testimonial->name,
            'role' => $testimonial->translated('role', $locale),
            'company' => $testimonial->company,
            'quote' => $testimonial->translated('quote', $locale),
            'category' => $testimonial->category,
            'category_label' => Testimonial::categories()[$testimonial->category] ?? $testimonial->category,
            'avatar_url' => $testimonial->avatarUrl(),
            'rating' => $testimonial->rating,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function post(Post $post, string $locale, bool $full = true): array
    {
        $data = [
            'id' => (string) $post->id,
            'slug' => $post->slug,
            'title' => $post->translated('title', $locale),
            'excerpt' => $post->translated('excerpt', $locale),
            'cover_url' => $post->coverUrl(),
            'category' => $post->category,
            'tags' => array_values($post->tags ?? []),
            'published_at' => $post->published_at?->toIso8601String(),
            'is_featured' => $post->is_featured,
            'reading_time' => $this->readingTime($post->translated('body', $locale)),
        ];

        if ($full) {
            $data['body'] = $post->translated('body', $locale);
        }

        return $data;
    }

    /**
     * @param  Collection<int, Project>  $projects
     * @return array<int, array<string, mixed>>
     */
    private function projectCategories(Collection $projects): array
    {
        return collect(Project::categories())
            ->map(fn (string $label, string $value): array => [
                'value' => $value,
                'label' => $label,
                'count' => $projects->where('category', $value)->count(),
            ])
            ->filter(fn (array $category): bool => $category['count'] > 0)
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function testimonialCategories(): array
    {
        return collect(Testimonial::categories())
            ->map(fn (string $label, string $value): array => [
                'value' => $value,
                'label' => $label,
            ])
            ->values()
            ->all();
    }

    private function readingTime(?string $body): int
    {
        $words = str_word_count(strip_tags((string) $body));

        return max(1, (int) ceil($words / 200));
    }
}

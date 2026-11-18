<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression cover for the "editing a project produced a 404" bug: the slug
 * input received the project title, so `/projects/Exam Platform` was stored and
 * the public detail page could never match it.
 */
class AdminSlugTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): void
    {
        $this->seed();
        $this->actingAs(User::query()->firstOrFail());
    }

    public function test_a_title_submitted_as_the_slug_is_slugified(): void
    {
        $this->actingAsAdmin();

        $project = Project::query()->where('slug', 'exam-platform')->firstOrFail();

        $this->put("/admin/projects/{$project->id}", $this->projectPayload([
            'title' => 'Exam Platform',
            'slug' => 'Exam Platform',
        ]))->assertRedirect('/admin/projects');

        $this->assertSame('exam-platform', $project->fresh()->slug);
    }

    public function test_an_unchanged_slug_survives_an_edit(): void
    {
        $this->actingAsAdmin();

        $project = Project::query()->where('slug', 'portfolio-website')->firstOrFail();

        $this->put("/admin/projects/{$project->id}", $this->projectPayload([
            'title' => 'Portfolio Website 2026',
            'slug' => 'portfolio-website',
        ]))->assertRedirect('/admin/projects');

        $fresh = $project->fresh();

        $this->assertSame('portfolio-website', $fresh->slug);
        $this->assertSame('Portfolio Website 2026', $fresh->title);
    }

    public function test_the_slug_field_value_is_rendered_in_the_edit_form(): void
    {
        $this->actingAsAdmin();

        $project = Project::query()->where('slug', 'exam-platform')->firstOrFail();

        $this->get("/admin/projects/{$project->id}/edit")
            ->assertOk()
            ->assertSee('name="slug"', false)
            ->assertSee('value="exam-platform"', false)
            ->assertSee('data-slug', false);
    }

    public function test_a_post_slug_is_slugified_too(): void
    {
        $this->actingAsAdmin();

        $post = Post::query()->firstOrFail();

        $this->put("/admin/posts/{$post->id}", [
            'title_id' => $post->title_id,
            'title_en' => $post->title_en,
            'slug' => 'Designing For Clarity',
            'category' => $post->category,
            'status' => $post->status,
            'excerpt_id' => $post->excerpt_id,
            'excerpt_en' => $post->excerpt_en,
            'body_id' => $post->body_id,
            'body_en' => $post->body_en,
            'cover_url' => $post->cover_url,
            'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
            'read_minutes' => (string) $post->read_minutes,
        ])->assertRedirect('/admin/posts');

        $this->assertSame('designing-for-clarity', $post->fresh()->slug);
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function projectPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Exam Platform',
            'title_en' => '',
            'description' => '',
            'description_en' => '',
            'excerpt_id' => '',
            'excerpt_en' => '',
            'content_id' => '',
            'content_en' => '',
            'slug' => 'exam-platform',
            'category' => 'web-app',
            'url' => '',
            'repo_url' => '',
            'image_url' => '',
            'gallery' => '',
            'tags' => 'Laravel',
            'status' => 'published',
            'is_featured' => '1',
            'sort_order' => '1',
            'started_at' => '',
        ], $overrides);
    }
}

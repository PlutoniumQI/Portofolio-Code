<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Skill;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PortfolioSeeder::class);
    }

    public function test_it_returns_the_full_localized_payload(): void
    {
        $response = $this->getJson('/api/portfolio');

        $response
            ->assertOk()
            ->assertJsonPath('locale', 'id')
            ->assertJsonStructure([
                'locale',
                'generated_at',
                'profile' => ['full_name', 'headline', 'intro', 'about', 'socials', 'stats'],
                'skills' => ['hard', 'soft'],
                'educations',
                'certificates',
                'experiences',
                'projects',
                'project_categories',
                'testimonials',
                'testimonial_categories',
                'posts',
            ]);

        $this->assertNotEmpty($response->json('projects'));
        $this->assertNotEmpty($response->json('skills.hard'));
        $this->assertNotEmpty($response->json('skills.soft'));
        $this->assertSame('Siswa PPLG & Desainer UI/UX', $response->json('profile.headline'));
        $this->assertSame('UI/UX Design', $response->json('skills.hard.0.name'));
        $this->assertSame('linkedin', array_key_first($response->json('profile.socials')));
        $this->assertSame(3, $response->json('profile.stats.projects_completed'));
    }

    public function test_english_locale_uses_english_values_and_falls_back_to_indonesian(): void
    {
        Skill::query()->where('name', 'Figma')->update(['description_en' => null]);

        $response = $this->getJson('/api/portfolio?locale=en');

        $response
            ->assertOk()
            ->assertJsonPath('locale', 'en')
            ->assertJsonPath('profile.headline', 'Software Engineering Student & UI/UX Designer');

        $this->assertSame(
            'Research, wireframes, prototyping, and usability testing.',
            $response->json('skills.hard.0.description'),
        );

        $figma = collect($response->json('skills.hard'))->firstWhere('name', 'Figma');

        $this->assertSame('Komponen, auto layout, design system, dan kolaborasi.', $figma['description']);
    }

    public function test_project_detail_is_available_by_slug_and_drafts_are_hidden(): void
    {
        $this->getJson('/api/projects/exam-platform?locale=en')
            ->assertOk()
            ->assertJsonPath('data.slug', 'exam-platform')
            ->assertJsonPath('data.title', 'Exam Platform')
            ->assertJsonStructure(['data' => ['content', 'gallery', 'is_featured', 'tags']]);

        Project::query()->where('slug', 'exam-platform')->update(['status' => Project::STATUS_DRAFT]);

        $this->getJson('/api/projects/exam-platform')->assertNotFound();
    }

    public function test_project_search_and_category_filters_are_applied(): void
    {
        $this->getJson('/api/projects?q=bilingual')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.slug', 'portfolio-website');

        $this->getJson('/api/projects?category=design-system')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.slug', 'modulia-design-system');
    }

    public function test_only_published_and_past_dated_posts_are_exposed(): void
    {
        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonPath('meta.total', 2);

        $this->getJson('/api/posts/designing-for-clarity')
            ->assertOk()
            ->assertJsonPath('data.slug', 'designing-for-clarity')
            ->assertJsonStructure(['data' => ['body', 'reading_time']]);
    }
}

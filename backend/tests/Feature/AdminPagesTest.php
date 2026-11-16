<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_for_an_authenticated_user(): void
    {
        $this->seed();

        $user = User::query()->firstOrFail();

        $this->actingAs($user);

        $this->get('/admin')->assertOk();

        $pages = [
            '/admin/projects',
            '/admin/projects/create',
            '/admin/experiences',
            '/admin/experiences/create',
            '/admin/skills',
            '/admin/skills/create',
            '/admin/education',
            '/admin/education/create',
            '/admin/certificates',
            '/admin/certificates/create',
            '/admin/testimonials',
            '/admin/testimonials/create',
            '/admin/posts',
            '/admin/posts/create',
            '/admin/messages',
            '/admin/site-profile',
            '/admin/publish',
            '/admin/profile',
        ];

        $editPages = [
            '/admin/projects/'.Project::query()->value('id').'/edit',
            '/admin/experiences/'.Experience::query()->value('id').'/edit',
            '/admin/skills/'.Skill::query()->value('id').'/edit',
            '/admin/education/'.Education::query()->value('id').'/edit',
            '/admin/certificates/'.Certificate::query()->value('id').'/edit',
            '/admin/testimonials/'.Testimonial::query()->value('id').'/edit',
            '/admin/posts/'.Post::query()->value('id').'/edit',
        ];

        foreach (array_merge($pages, $editPages) as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin/projects')->assertRedirect('/admin/login');
    }
}

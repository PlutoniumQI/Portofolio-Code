<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\TestimonialSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_for_an_authenticated_administrator(): void
    {
        $this->seed();

        $user = User::query()->firstOrFail();

        $this->assertTrue($user->is_admin);

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
            '/admin/testimonial-submissions',
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

    public function test_the_login_page_renders_for_guests(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Sign in');
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin/projects')->assertRedirect('/admin/login');
    }

    public function test_non_administrators_are_logged_out_and_redirected(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_an_administrator_can_sign_in_and_out(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'owner@example.com',
            'password' => Hash::make('super-secret-password'),
        ]);

        $this->post('/admin/login', [
            'email' => 'owner@example.com',
            'password' => 'super-secret-password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);

        $this->post('/admin/logout')->assertRedirect('/admin/login');

        $this->assertGuest();
    }

    public function test_projects_can_be_created_updated_reordered_and_deleted(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $this->post('/admin/projects', [
            'title' => 'Admin built project',
            'title_en' => 'Admin built project',
            'slug' => 'admin-built-project',
            'category' => 'web-app',
            'status' => 'published',
            'is_featured' => '1',
            'tags' => 'Laravel, Next.js',
            'gallery' => "https://example.com/one.jpg\nhttps://example.com/two.jpg",
        ])->assertRedirect('/admin/projects');

        $project = Project::query()->where('slug', 'admin-built-project')->sole();

        $this->assertSame(['Laravel', 'Next.js'], $project->tags);
        $this->assertSame(
            ['https://example.com/one.jpg', 'https://example.com/two.jpg'],
            $project->gallery,
        );
        $this->assertTrue($project->is_featured);
        $this->assertSame(1, $project->sort_order);

        $this->put("/admin/projects/{$project->id}", [
            'title' => 'Renamed project',
            'title_en' => 'Renamed project',
            'slug' => 'admin-built-project',
            'category' => 'web-app',
            'status' => 'published',
            'tags' => 'Laravel',
        ])->assertRedirect('/admin/projects');

        $this->assertSame('Renamed project', $project->fresh()->title);

        $second = Project::factory()->create(['sort_order' => 10]);

        $this->postJson('/admin/projects/reorder', [
            'ids' => [$second->id, $project->id],
        ])->assertOk();

        $this->assertSame(1, $second->fresh()->sort_order);
        $this->assertSame(2, $project->fresh()->sort_order);

        $this->delete("/admin/projects/{$project->id}")
            ->assertRedirect('/admin/projects');

        $this->assertModelMissing($project);
    }

    public function test_the_site_profile_can_be_saved(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $this->put('/admin/site-profile', [
            'full_name' => 'Updated Owner',
            'headline_id' => 'Pengembang',
            'headline_en' => 'Developer',
            'is_available' => '1',
            'years_learning' => 3,
            'tools_mastered' => 9,
        ])->assertRedirect();

        $profile = Profile::query()->sole();

        $this->assertSame('Updated Owner', $profile->full_name);
        $this->assertTrue($profile->is_available);
        $this->assertSame(3, $profile->years_learning);
    }

    public function test_opening_a_message_marks_it_as_read(): void
    {
        $user = User::factory()->admin()->create();
        $message = Message::query()->create([
            'type' => Message::TYPE_CONTACT,
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'Hello',
            'message' => 'A message for the administrator.',
            'is_read' => false,
            'is_approved' => true,
        ]);

        $this->actingAs($user)
            ->get("/admin/messages/{$message->id}")
            ->assertOk()
            ->assertSee('A message for the administrator.');

        $this->assertTrue($message->fresh()->is_read);
    }

    public function test_a_pending_submission_can_be_published_or_rejected(): void
    {
        $user = User::factory()->admin()->create();

        $submission = TestimonialSubmission::query()->create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'role' => 'Dosen',
            'company' => 'SMKN 2 Banjarmasin',
            'category' => 'teacher',
            'rating' => 5,
            'quote' => 'Resky is a careful and curious student.',
            'locale' => 'en',
            'status' => TestimonialSubmission::STATUS_PENDING,
        ]);

        $this->actingAs($user)
            ->post("/admin/testimonial-submissions/{$submission->id}/publish")
            ->assertRedirect("/admin/testimonial-submissions/{$submission->id}");

        $submission->refresh();

        $this->assertSame(TestimonialSubmission::STATUS_APPROVED, $submission->status);
        $this->assertNotNull($submission->reviewed_at);

        $testimonial = Testimonial::query()->sole();

        $this->assertSame('Dewi Lestari', $testimonial->name);
        $this->assertSame('Resky is a careful and curious student.', $testimonial->quote_id);
        $this->assertSame('Resky is a careful and curious student.', $testimonial->quote_en);
        $this->assertSame('teacher', $testimonial->category);
        $this->assertTrue($testimonial->is_active);

        $other = TestimonialSubmission::query()->create([
            'name' => 'Spam',
            'email' => 'spam@example.com',
            'category' => 'peer',
            'rating' => 1,
            'quote' => 'This submission should be rejected.',
            'locale' => 'id',
            'status' => TestimonialSubmission::STATUS_PENDING,
        ]);

        $this->post("/admin/testimonial-submissions/{$other->id}/reject")
            ->assertRedirect();

        $this->assertSame(TestimonialSubmission::STATUS_REJECTED, $other->fresh()->status);
        $this->assertSame(1, Testimonial::query()->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_render_for_an_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get('/admin')->assertOk();
        $this->get('/admin/projects')->assertOk();
        $this->get('/admin/projects/create')->assertOk();
        $this->get('/admin/experiences')->assertOk();
        $this->get('/admin/experiences/create')->assertOk();
        $this->get('/admin/publish')->assertOk();
        $this->get('/admin/profile')->assertOk();
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get('/admin/projects')->assertRedirect('/admin/login');
    }
}

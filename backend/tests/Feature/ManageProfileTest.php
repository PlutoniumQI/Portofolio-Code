<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageProfile;
use App\Models\Profile;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ManageProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_profile_singleton_can_be_updated(): void
    {
        $this->actingAs(User::factory()->create());

        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $profile = Profile::current();

        Livewire::test(ManageProfile::class)
            ->assertOk()
            ->set('data.full_name', 'Resky Updated')
            ->set('data.linkedin_url', 'https://www.linkedin.com/in/resky')
            ->call('save')
            ->assertHasNoErrors();

        $profile->refresh();

        $this->assertSame('Resky Updated', $profile->full_name);
        $this->assertSame('https://www.linkedin.com/in/resky', $profile->linkedin_url);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExportPortfolioTest extends TestCase
{
    use RefreshDatabase;

    private string $exportDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exportDirectory = storage_path('framework/testing/portfolio-export');

        config([
            'portfolio.export_json' => $this->exportDirectory.'/portfolio.json',
            'portfolio.export_images' => $this->exportDirectory.'/images',
        ]);

        File::deleteDirectory($this->exportDirectory);
        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->exportDirectory);

        parent::tearDown();
    }

    public function test_it_exports_public_content_and_copies_local_images(): void
    {
        Storage::disk('public')->put('projects/example.webp', 'fake-image');

        Project::factory()->create([
            'title' => 'Published project',
            'status' => Project::STATUS_PUBLISHED,
            'sort_order' => 2,
            'image_path' => 'projects/example.webp',
        ]);

        Project::factory()->create([
            'title' => 'Coming soon project',
            'status' => Project::STATUS_COMING_SOON,
            'sort_order' => 1,
            'image_url' => 'https://raw.githubusercontent.com/user/repo/main/image.webp',
        ]);

        Project::factory()->draft()->create([
            'title' => 'Draft project',
        ]);

        Experience::factory()->create([
            'title' => 'Active experience',
            'is_active' => true,
        ]);

        Experience::factory()->inactive()->create([
            'title' => 'Hidden experience',
        ]);

        $this->artisan('portfolio:export')->assertSuccessful();

        $payload = json_decode(File::get($this->exportDirectory.'/portfolio.json'), true);

        $this->assertCount(2, $payload['projects']);
        $this->assertSame('Coming soon project', $payload['projects'][0]['title']);
        $this->assertSame('https://raw.githubusercontent.com/user/repo/main/image.webp', $payload['projects'][0]['imageUrl']);
        $this->assertSame('/images/projects/example.webp', $payload['projects'][1]['imageUrl']);
        $this->assertFileExists($this->exportDirectory.'/images/example.webp');

        $this->assertCount(1, $payload['experiences']);
        $this->assertSame('Active experience', $payload['experiences'][0]['title']);
        $this->assertArrayNotHasKey('is_active', $payload['experiences'][0]);
    }
}

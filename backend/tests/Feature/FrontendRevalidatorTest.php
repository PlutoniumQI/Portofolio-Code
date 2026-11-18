<?php

namespace Tests\Feature;

use App\Services\FrontendRevalidator;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class FrontendRevalidatorTest extends TestCase
{
    public function test_it_revalidates_every_public_page(): void
    {
        config([
            'portfolio.frontend.revalidate_url' => 'https://frontend.test/api/revalidate',
            'portfolio.frontend.revalidate_secret' => 'test-secret',
        ]);

        Http::fake([
            'frontend.test/*' => Http::response(['revalidated' => true]),
        ]);

        $result = (new FrontendRevalidator)->revalidate();

        $this->assertTrue($result['ok']);

        Http::assertSent(function ($request): bool {
            return $request->url() === 'https://frontend.test/api/revalidate'
                && $request->hasHeader('X-Revalidate-Secret', 'test-secret')
                && $request->data()['paths'] === FrontendRevalidator::PATHS;
        });
    }

    public function test_it_reports_missing_configuration(): void
    {
        config([
            'portfolio.frontend.revalidate_url' => null,
            'portfolio.frontend.revalidate_secret' => null,
        ]);

        $result = (new FrontendRevalidator)->revalidate();

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('FRONTEND_REVALIDATE_URL', $result['message']);
    }
}

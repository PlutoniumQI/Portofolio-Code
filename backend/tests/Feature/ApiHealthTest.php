<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiHealthTest extends TestCase
{
    public function test_the_health_endpoint_returns_ok(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'service' => 'portfolio-api',
                'environment' => 'testing',
            ])
            ->assertJsonStructure([
                'status',
                'service',
                'environment',
                'timestamp',
            ]);
    }

    public function test_an_allowed_frontend_origin_receives_cors_headers(): void
    {
        config(['cors.allowed_origins' => ['https://portfolio.test']]);

        $this->withHeaders(['Origin' => 'https://portfolio.test'])
            ->getJson('/api/health')
            ->assertOk()
            ->assertHeader('Access-Control-Allow-Origin', 'https://portfolio.test');
    }

    public function test_an_unknown_origin_does_not_receive_cors_headers(): void
    {
        // Configure more than one origin: the CORS library echoes a single
        // allowed origin without checking the request, so a single value would
        // make this test meaningless.
        config(['cors.allowed_origins' => [
            'https://portfolio.test',
            'https://www.portfolio.test',
        ]]);

        $response = $this->withHeaders(['Origin' => 'https://evil.test'])
            ->getJson('/api/health');

        $response->assertOk();

        $this->assertNull($response->headers->get('Access-Control-Allow-Origin'));
    }

    public function test_a_preflight_request_is_answered_for_the_frontend_origin(): void
    {
        config(['cors.allowed_origins' => ['https://portfolio.test']]);

        $this->call('OPTIONS', '/api/health', [], [], [], [
            'HTTP_ORIGIN' => 'https://portfolio.test',
            'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
        ])
            ->assertNoContent()
            ->assertHeader('Access-Control-Allow-Origin', 'https://portfolio.test');
    }
}

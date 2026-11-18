<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FrontendRevalidator
{
    /**
     * Every public page that renders portfolio content. The frontend expands
     * these into both locales and also refreshes project/blog detail routes.
     *
     * @var array<int, string>
     */
    public const PATHS = [
        '/',
        '/about',
        '/blog',
        '/certificates',
        '/contact',
        '/cv',
        '/experience',
        '/projects',
        '/skills',
        '/testimonials',
    ];

    /**
     * Ask the frontend to revalidate after the current response is flushed.
     * Runs at most once per request, regardless of how many models changed.
     */
    public function schedule(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        if (blank(config('portfolio.frontend.revalidate_url')) || blank(config('portfolio.frontend.revalidate_secret'))) {
            return;
        }

        $request = app('request');

        if ($request->attributes->get('frontend.revalidation.scheduled')) {
            return;
        }

        $request->attributes->set('frontend.revalidation.scheduled', true);

        app()->terminating(fn () => $this->revalidate());
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function revalidate(): array
    {
        $url = config('portfolio.frontend.revalidate_url');
        $secret = config('portfolio.frontend.revalidate_secret');

        if (blank($url) || blank($secret)) {
            return [
                'ok' => false,
                'message' => 'Set FRONTEND_REVALIDATE_URL and FRONTEND_REVALIDATE_SECRET first.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'X-Revalidate-Secret' => $secret,
            ])
                ->acceptJson()
                ->connectTimeout(3)
                ->timeout(10)
                ->post($url, ['paths' => self::PATHS]);

            if ($response->failed()) {
                return [
                    'ok' => false,
                    'message' => "Vercel returned HTTP {$response->status()}: {$response->body()}",
                ];
            }

            return [
                'ok' => true,
                'message' => 'The live site is refreshing now.',
            ];
        } catch (\Throwable $exception) {
            Log::warning('Frontend revalidation failed', ['exception' => $exception]);

            return [
                'ok' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }
}

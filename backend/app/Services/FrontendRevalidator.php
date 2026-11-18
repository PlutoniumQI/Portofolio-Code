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
        '/guestbook',
        '/projects',
        '/skills',
        '/testimonials',
    ];

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
                ->timeout(15)
                ->post($url, ['paths' => self::PATHS]);

            if ($response->failed()) {
                return [
                    'ok' => false,
                    'message' => "Vercel returned HTTP {$response->status()}: {$response->body()}",
                ];
            }

            return [
                'ok' => true,
                'message' => 'The live site will refresh on its next request.',
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

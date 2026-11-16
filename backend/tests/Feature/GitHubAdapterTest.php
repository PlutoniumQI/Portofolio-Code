<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;
use Tests\TestCase;

class GitHubAdapterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'filesystems.disks.github' => [
                'driver' => 'github',
                'token' => 'test-token',
                'owner' => 'PlutoniumQI',
                'repo' => 'portfolio-assets',
                'branch' => 'main',
                'root' => 'portfolio',
                'api_url' => 'https://api.github.com',
                'url' => 'https://raw.githubusercontent.com/PlutoniumQI/portfolio-assets/main/portfolio',
                'throw' => true,
                'report' => false,
            ],
        ]);

        Storage::forgetDisk('github');
    }

    public function test_it_commits_an_upload_and_returns_a_raw_github_url(): void
    {
        Http::fake(function (Request $request) {
            if ($request->method() === 'GET') {
                return Http::response([], 404);
            }

            return Http::response(['content' => ['sha' => 'new-sha']], 201);
        });

        $disk = Storage::disk('github');
        $disk->put('projects/cover.jpg', 'binary-image');

        $this->assertSame(
            'https://raw.githubusercontent.com/PlutoniumQI/portfolio-assets/main/portfolio/projects/cover.jpg',
            $disk->url('projects/cover.jpg'),
        );

        Http::assertSent(function (Request $request): bool {
            if ($request->method() !== 'PUT') {
                return false;
            }

            return str_contains(
                $request->url(),
                '/repos/PlutoniumQI/portfolio-assets/contents/portfolio/projects/cover.jpg',
            )
                && $request->data()['content'] === base64_encode('binary-image')
                && $request->data()['branch'] === 'main'
                && ! isset($request->data()['sha'])
                && $request->hasHeader('Authorization', 'Bearer test-token');
        });
    }

    public function test_overwriting_a_file_sends_the_existing_sha(): void
    {
        Http::fake(function (Request $request) {
            if ($request->method() === 'GET') {
                return Http::response([
                    'type' => 'file',
                    'path' => 'portfolio/projects/cover.jpg',
                    'sha' => 'old-sha',
                    'size' => 12,
                ], 200);
            }

            return Http::response(['content' => ['sha' => 'new-sha']], 200);
        });

        Storage::disk('github')->put('projects/cover.jpg', 'new-bytes');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
            && ($request->data()['sha'] ?? null) === 'old-sha');
    }

    public function test_failed_uploads_raise_an_exception(): void
    {
        Http::fake(function (Request $request) {
            if ($request->method() === 'GET') {
                return Http::response([], 404);
            }

            return Http::response(['message' => 'Bad credentials'], 401);
        });

        $this->expectException(UnableToWriteFile::class);

        Storage::disk('github')->put('projects/cover.jpg', 'binary-image');
    }
}

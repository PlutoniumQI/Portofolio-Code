<?php

namespace App\Console\Commands;

use App\Services\PortfolioService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportPortfolioFallback extends Command
{
    protected $signature = 'portfolio:fallback';

    protected $description = 'Write a bilingual JSON snapshot used when the Next.js site cannot reach the API';

    public function handle(PortfolioService $portfolio): int
    {
        $path = config('portfolio.fallback_json');

        File::ensureDirectoryExists(dirname($path));

        File::put(
            $path,
            json_encode([
                'id' => $portfolio->payload('id'),
                'en' => $portfolio->payload('en'),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE).PHP_EOL,
        );

        $this->info("Portfolio fallback written to {$path}");

        return self::SUCCESS;
    }
}

<?php

namespace App\Models\Concerns;

use App\Services\FrontendRevalidator;

/**
 * Triggers a frontend revalidation after content changes, at most once per
 * admin request. The call runs after the response is sent, so saving never
 * waits on the Next.js deployment.
 */
trait RevalidatesFrontend
{
    protected static function bootRevalidatesFrontend(): void
    {
        static::saved(fn () => app(FrontendRevalidator::class)->schedule());
        static::deleted(fn () => app(FrontendRevalidator::class)->schedule());
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /**
     * Resolve a stored upload or an external URL into something a browser can
     * load. Only legacy records still have a path; new content stores public
     * URLs directly.
     */
    public static function url(?string $path, ?string $disk = null, ?string $external = null): ?string
    {
        if (filled($path)) {
            $disk = $disk ?: self::defaultDisk();

            try {
                $url = Storage::disk($disk)->url($path);
            } catch (\Throwable) {
                return filled($external) ? $external : null;
            }

            if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
                $url = url($url);
            }

            return $url;
        }

        return filled($external) ? $external : null;
    }

    public static function defaultDisk(): string
    {
        return 'public';
    }
}

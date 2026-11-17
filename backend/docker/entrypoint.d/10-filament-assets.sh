#!/bin/sh
set -e

# Filament assets are ignored by git and must be published into the running
# container. This runs before nginx/php-fpm start.
echo "Publishing Filament assets..."
php artisan filament:assets --ansi

# Keep legacy public-disk uploads browsable. New media is stored as external
# URLs, so this link only matters for older records.
php artisan storage:link --ansi || true

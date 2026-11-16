#!/bin/sh
set -e

# Filament assets are ignored by git and must be published into the running
# container. This runs before nginx/php-fpm start.
echo "Publishing Filament assets..."
php artisan filament:assets --ansi

# Keep the legacy public-disk uploads browsable during the migration to the
# GitHub uploader. The Render filesystem is ephemeral, so this is temporary.
php artisan storage:link --ansi || true

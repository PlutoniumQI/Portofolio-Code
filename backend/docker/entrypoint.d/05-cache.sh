#!/bin/sh
set -e

# Cache configuration and Blade views for production. Route caching is skipped
# because package/plugin routes may contain closures that cannot be serialized.
if [ "${APP_ENV:-production}" = "production" ]; then
    echo "Caching Laravel config and views..."
    php artisan config:cache --ansi
    php artisan view:cache --ansi
fi

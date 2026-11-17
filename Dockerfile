# Production image for the Laravel + Filament backend.
# Target: Apply.Build (Docker runtime), database: Neon Postgres.
#
# Apply.Build builds from the repository root, so this file lives at the root
# and copies the Laravel application out of backend/.
FROM serversideup/php:8.4-fpm-nginx

# Filament needs ext-intl; production uses Postgres.
USER root
RUN install-php-extensions intl pdo_pgsql

WORKDIR /var/www/html

# Install PHP dependencies first so this layer is cached.
COPY --chown=www-data:www-data backend/composer.json backend/composer.lock ./
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy the application and build the optimized autoloader. A throwaway APP_KEY
# lets package:discover boot Laravel during the build; runtime uses the real key.
COPY --chown=www-data:www-data backend/ .
RUN mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
    && COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize --no-dev --no-scripts \
    && APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache public

# Startup scripts: cache config/views, publish Filament assets, migrate (opt-in),
# and seed (opt-in).
COPY --chmod=755 backend/docker/entrypoint.d/ /etc/entrypoint.d/

USER www-data

# Apply.Build routes to the port configured in the app settings; the image
# listens on 8080. Set NGINX_HTTP_PORT=8080 and SSL_MODE=off.
EXPOSE 8080

# serversideup/php starts nginx + php-fpm with its own entrypoint.

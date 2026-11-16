# Production image for the Laravel + Filament backend.
# Target: Render Free (Docker runtime), database: Neon Postgres.
FROM serversideup/php:8.4-fpm-nginx

# Filament needs ext-intl; production uses Postgres.
USER root
RUN install-php-extensions intl pdo_pgsql

WORKDIR /var/www/html

# Install PHP dependencies first so this layer is cached.
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy the application and build the optimized autoloader. A throwaway APP_KEY
# lets package:discover boot Laravel during the build; runtime uses the real key.
COPY --chown=www-data:www-data . .
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
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/

USER www-data

# Render expects the service to listen on the port from its PORT env var,
# which defaults to 10000. render.yaml sets NGINX_HTTP_PORT to match.
EXPOSE 10000

# serversideup/php starts nginx + php-fpm with its own entrypoint.

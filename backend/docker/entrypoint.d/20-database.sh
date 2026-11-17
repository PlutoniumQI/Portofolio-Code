#!/bin/sh
set -e

# Run migrations on container start when explicitly enabled. Apply.Build runs a
# single container, so this is safe. Neon may be cold, hence the retries.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    attempt=1

    until php artisan migrate --force --ansi; do
        if [ "$attempt" -ge 5 ]; then
            echo "Database migrations failed after $attempt attempts." >&2
            exit 1
        fi

        attempt=$((attempt + 1))
        echo "Database not ready; retrying migrations in 5 seconds..." >&2
        sleep 5
    done
fi

# Seed only when explicitly requested. Seeders must be idempotent because this
# block runs again on every restart while SEED_DATABASE=true.
if [ "${SEED_DATABASE:-false}" = "true" ]; then
    php artisan db:seed --force --ansi
fi

# Backend — Filament admin + API

Laravel 13 + Filament 5. Runs locally with SQLite and the public disk; production runs on **Render
Free** (Docker) at `api.<domain>` with **Neon Postgres** and a public GitHub assets repository. Read
`../AGENTS.md` first and [`../docs/deploy-render-neon.md`](../docs/deploy-render-neon.md) for the
deployment runbook.

## Local development

- Admin: `php artisan serve --host=127.0.0.1 --port=8000` → <http://127.0.0.1:8000/admin>
- First-time setup: `composer install`, copy `.env.example` to `.env`, `php artisan key:generate`,
  create `database/database.sqlite`, `php artisan migrate --seed`, `php artisan storage:link`.
- Legacy export: `php artisan portfolio:export` writes `../src/data/portfolio.json` and copies local
  uploads. Phase 2 replaces it with `GET /api/portfolio` and the revalidation webhook.
- Composer and all `artisan` commands need a PHP build with `ext-intl`. On this Windows machine:

  ```powershell
  $env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
  ```

  From the repo root, `npm run dev:backend` and `npm run export` already use that binary.

- Verify with `php artisan test`, `vendor\bin\pint --test`, and
  `php artisan route:list --path=api`.

## Production

- `../render.yaml` defines the `portfolio-api` service. `Dockerfile` uses
  `serversideup/php:8.4-fpm-nginx` and installs `intl` + `pdo_pgsql`.
- Environment reference: `.env.production.example`. Real values (APP_KEY, DB_URL, ADMIN_PASSWORD,
  GITHUB_TOKEN) live only in Render. Never commit `.env`.
- Startup scripts in `docker/entrypoint.d/` cache config/views, publish Filament assets, run
  migrations when `RUN_MIGRATIONS=true`, and seed when `SEED_DATABASE=true`. Seeders must be
  idempotent because restarts re-run them while the flag is on.
- Sessions and cache use Postgres; the queue is `sync`; logs go to stderr.
- CORS is defined in `config/cors.php` and accepts only `FRONTEND_ALLOWED_ORIGINS`. Do not use `*`
  in production.
- The admin user is seeded from `ADMIN_EMAIL` / `ADMIN_PASSWORD`. Changing those env vars later does
  not update an existing password; use the Filament profile page.
- Uploads use `GITHUB_TOKEN` (fine-grained, Contents: Read and write, one repository). If the token
  is empty locally, uploads fall back to the `public` disk.
- The API lives in `routes/api.php`. It currently exposes `GET /api/health`; phase 2 adds the content
  and message endpoints.
- Render routes traffic to port `10000`; `NGINX_HTTP_PORT=10000` and `SSL_MODE=off` are required for
  the `serversideup/php` image.

Do not add a Vercel build for this app, a PHP runtime on Vercel, or a queue worker on Render Free
without checking the plan and the free instance limits.

# Backend — Filament admin + API

Laravel 13 + Filament 5. Runs locally with SQLite and the public disk; production runs on
**Vercel Containers** at `api.<domain>` with **Neon Postgres**, and media is pasted in as public
URLs. Read `../AGENTS.md` first and
[`../docs/deploy-vercel-neon.md`](../docs/deploy-vercel-neon.md) for the deployment runbook.

## Local development

- Admin: `php artisan serve --host=127.0.0.1 --port=8000` → <http://127.0.0.1:8000/admin>
- First-time setup: `composer install`, copy `.env.example` to `.env`, `php artisan key:generate`,
  create `database/database.sqlite`, `php artisan migrate --seed`, `php artisan storage:link`.
- Fallback snapshot: `php artisan portfolio:fallback` writes
  `../src/data/portfolio-fallback.json`. The Next.js site reads it during `next build` and when the
  API is unreachable. Regenerate and commit it after changing seed content.
- `php artisan portfolio:export` is the retired static pipeline. It still writes
  `../src/data/portfolio.json` and copies local uploads for reference, but the frontend no longer
  reads that file. Do not extend it.
- Composer and all `artisan` commands need a PHP build with `ext-intl`. On this Windows machine:

  ```powershell
  $env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
  ```

  From the repo root, `npm run dev:backend`, `npm run export`, and `npm run fallback` already use that
  binary.

- Verify with `php artisan test`, `vendor\bin\pint --test`, and
  `php artisan route:list --path=api`.

## Production

- `Dockerfile.vercel` is the Vercel Container build file. It uses FrankenPHP 8.4, installs
  `intl` + `zip` + `pdo_pgsql`, publishes Filament assets, and precompiles Blade views at build
  time. `Caddyfile` serves `public/` on Vercel's `$PORT`, and `vercel.json` pins the framework to
  `container` so the Vite scaffolding in this directory is never detected instead.
- Environment reference: `.env.production.example`. Real values (APP_KEY, DB_URL, revalidation
  secret) live only in Vercel. Never commit `.env`.
- Migrations and seeding are a one-off command from a workstation
  (`php artisan migrate --seed --force`). Never run them from the container: Vercel can start several
  instances concurrently. Seeders must stay idempotent.
- Sessions and cache use Postgres; the queue is `sync`; logs go to stderr. The container filesystem
  is ephemeral, so nothing may depend on local files.
- CORS is defined in `config/cors.php` and accepts only `FRONTEND_ALLOWED_ORIGINS`. Do not use `*`
  in production.
- The admin user is seeded once from `ADMIN_EMAIL` / `ADMIN_PASSWORD`. Changing those env vars later
  does not update an existing password; use the Filament profile page.
- The admin is URL-only: paste public image/PDF URLs into the cover, gallery, certificate, avatar,
  blog cover, and CV fields. There is no upload disk and no GitHub token in production.
- The API lives in `routes/api.php`:
  - `GET /api/health` — liveness.
  - `GET /api/portfolio?locale=id|en` — localized profile, skills, education, certificates,
    experiences, projects, testimonials, and published posts.
  - `GET /api/projects`, `GET /api/projects/{slug}` — search (`q`), filter (`category`, `featured`),
    and project detail.
  - `GET /api/posts`, `GET /api/posts/{slug}` — published blog posts only.
  - `GET /api/guestbook` — approved guestbook entries only.
  - `POST /api/messages` — contact/guestbook submissions, throttled 5/minute per IP, with a
    honeypot and guestbook moderation.
- Admin sections: **Projects**, **Experience**, **Blog posts**, **Skills**, **Education**,
  **Certificates**, **Testimonials**, **Messages & guestbook**, plus **Profile & CV** and
  **Publish changes** under Settings. Project/experience/post/certificate/testimonial forms use
  bilingual tabs; English falls back to Indonesian in the API.
- **Settings → Publish changes** calls `App\Services\FrontendRevalidator`, which posts to
  `FRONTEND_REVALIDATE_URL` (the Vercel `/api/revalidate` route) with
  `FRONTEND_REVALIDATE_SECRET`. Leave both empty locally; ISR refreshes on its own schedule.

Do not add startup scripts, queue workers, or persistent-disk assumptions. Container Functions run
in `sin1`, scale to zero after about five minutes idle, and cap request bodies at 4.5 MB.

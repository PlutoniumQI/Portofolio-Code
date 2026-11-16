<!-- BEGIN:nextjs-agent-rules -->
# This is NOT the Next.js you know

This version has breaking changes — APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` before writing any code. Heed deprecation notices.
<!-- END:nextjs-agent-rules -->

# Portfolio project guide

## Architecture

This repo contains two apps:

- **`/` (Next.js 16, App Router)** — the public site. Deployed to Vercel Hobby at the user's domain.
- **`backend/` (Laravel 13 + Filament 5)** — the admin and API. Local development uses SQLite and the
  public disk; production runs on **Render Free (Docker)** at `api.<domain>`, with **Neon Postgres**
  and a public **GitHub assets repository**.

Target content flow:

```text
Filament edit -> Neon Postgres -> GET /api/portfolio -> Next.js ISR -> Vercel
uploads       -> GitHub assets repo -> raw.githubusercontent.com URLs
Publish       -> POST /api/revalidate on Vercel
```

**Migration status:** phase 1 infrastructure and the phase 2 backend (content models, Filament admin,
public API, GitHub uploader) are in place. The public site is still the existing one-page app that
reads generated JSON until the API-backed bilingual frontend lands. The legacy pipeline below still
runs during the transition and must not be extended:

```text
Filament edit -> SQLite -> php artisan portfolio:export
             -> src/data/portfolio.json
             -> public/images/projects/*
             -> git commit/push -> Vercel rebuild
```

`src/lib/portfolio.ts` still imports the generated JSON. Phase 2 replaces the export command with the
API and the revalidation webhook. Do not reintroduce Contentful.

## Infrastructure files

- `render.yaml` — Render Blueprint for `portfolio-api` (Docker, Free plan, health check `/up`).
- `backend/Dockerfile` — `serversideup/php:8.4-fpm-nginx`; installs `intl` and `pdo_pgsql`.
- `backend/docker/entrypoint.d/` — startup scripts: config/view cache, Filament assets, opt-in
  migrations and seeding.
- `backend/config/cors.php` — allows only `FRONTEND_ALLOWED_ORIGINS`.
- `backend/routes/api.php` — public API. Currently `GET /api/health`; content routes arrive in phase 2.
- `backend/.env.production.example` — production env reference. Real secrets live only in Render.
- `.env.example` — frontend/Vercel env reference.
- `docs/deploy-render-neon.md` — full runbook for Neon, the GitHub token, Render, Vercel, DNS,
  keep-alive, security, and troubleshooting.

## Source of truth and generated files

- Edit projects/experiences in the Filament admin (`http://127.0.0.1:8000/admin` locally,
  `https://api.<domain>/admin` in production), not in JSON.
- During the transition, `src/data/portfolio.json` is **generated** by `php artisan portfolio:export`.
  Do not hand-edit it.
- `public/images/projects/*` is still generated from local uploads. Phase 2 moves images to GitHub
  raw URLs.
- `src/app/page.tsx` still owns the static site copy: hero, skills, about text, social links, stats.
  Only projects and experiences come from the export.
- If a model field changes, update all affected layers together:
  1. `backend/database/migrations/*`
  2. `backend/app/Models/*`
  3. `backend/app/Filament/Resources/.../Schemas/*Form.php`
  4. `backend/app/Filament/Resources/.../Tables/*Table.php`
  5. `backend/app/Console/Commands/ExportPortfolio.php` (legacy, until phase 2)
  6. the API resource/serializer (from phase 2)
  7. `src/lib/portfolio.ts` interfaces

## Commands

Run frontend commands from the repo root:

```powershell
npm run dev          # Next.js dev server
npm run lint
npx tsc --noEmit
npm run build
npm run dev:backend  # PHP admin at http://127.0.0.1:8000/admin
npm run export       # legacy: writes JSON + copies local uploads
```

Run backend commands from `backend/`:

```powershell
php artisan test
vendor\bin\pint --test
php artisan migrate --seed
php artisan route:list --path=api
php artisan portfolio:export
```

Optional Docker build check (requires Docker Desktop):

```powershell
docker build -t portfolio-api .\backend
```

**PHP caveat:** all backend commands need `ext-intl`; the default `php` on this Windows machine
(Herd Lite) does not have it. `npm run dev:backend` and `npm run export` already point at the full
Herd build. For direct commands, prefix PATH:

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
```

Without that, Filament tables fail with `The "intl" PHP extension is required to use the [format] method.`

## Backend rules

- Production is public: Render Free Docker + Neon Postgres. Keep `APP_DEBUG=false`, `APP_URL` on
  HTTPS, and secrets only in Render environment variables.
- Local development remains SQLite + the `public` disk. Never commit `backend/.env`.
- `render.yaml` is the source of truth for non-secret production env vars. Add new production
  variables there **and** to `backend/.env.production.example`.
- Sessions and cache use the database in production; the queue is `sync`. Do not add a queue worker
  on Render Free without checking the plan.
- Migrations run at container start when `RUN_MIGRATIONS=true`. Seed only with `SEED_DATABASE=true`;
  seeders must be idempotent because restarts re-run them while the flag is on.
- Filament assets are git-ignored and published at startup by
  `backend/docker/entrypoint.d/10-filament-assets.sh`.
- The admin login is seeded from `ADMIN_EMAIL` / `ADMIN_PASSWORD`. Changing those env vars later does
  not change an existing password; use the Filament profile page.
- CORS is restricted to `FRONTEND_ALLOWED_ORIGINS`. Do not use `*` in production.
- Project statuses: `draft` (not exported), `published`, `coming_soon` (exported with a badge).
  Inactive experiences are not exported.
- `sort_order` is the display order and is drag-reorderable in the Filament tables.
- The “Publish to site” page still calls `portfolio:export`; keep it working until phase 2 replaces it
  with the revalidate webhook.
- Do not add a Vercel build for `backend/` or PHP hosting on Vercel. The backend runs on Render as a
  Docker service.

## Image rules

- Production target: uploads are committed to the public `portfolio-assets` GitHub repo via the
  Contents API, using a fine-grained token (`GITHUB_TOKEN`, Contents: Read and write) limited to that
  repo. The token is never committed.
- Until the uploader ships, paste **raw** GitHub URLs
  (`https://raw.githubusercontent.com/<user>/<repo>/<branch>/<path>`), **not**
  `github.com/.../blob/...` pages. Private repos will not render for visitors.
- Local development: when `GITHUB_TOKEN` is empty, uploads fall back to Laravel's `public` disk
  (`backend/storage/app/public`, linked with `php artisan storage:link`).
- Upload wins if both the local upload and the external URL are set. The legacy export passes external
  URLs through without downloading them.
- Upload size is capped by `PORTFOLIO_MAX_UPLOAD_KB` (10 MB in production, 2 MB local). Keep
  `PHP_UPLOAD_MAX_FILE_SIZE` and `PHP_POST_MAX_SIZE` above it.
- GitHub's Contents API accepts files up to 100 MB, but images should be compressed and kept much
  smaller. Keep the assets repo well under 1 GB.
- This PHP build has no JPEG/WebP GD support and no Imagick. Do not add server-side image conversion.
- Keep plain `<img>`; no `next/image` remote domains are configured. New remote hosts work without
  config.

## Frontend invariants

- `src/components/ClientEffects.tsx` drives animations imperatively. Preserve the IDs/classes it
  queries: `#navbar`, `#menuToggle`, `#mobileMenu`, `#glow`, `#tw`, `#liveClock`, `#backToTop`,
  `.reveal`, `.counter`, `.project-card-glow`, `#hero .reveal`.
- `src/app/globals.css` uses Tailwind v4: `@import "tailwindcss"` + `@theme inline`. There is no
  `tailwind.config.*`; custom keyframes/utilities live in that CSS file. Preserve custom classes such
  as `.hero-orb`, `.glass-card`, `.gradient-border-ring`, `.btn-shimmer`, `.mq`, `.fl`, `.fl2`, `.fl3`,
  `.scroll-indicator`, `.hamburger-line`, `.nav-link`, `.mobile-nav-link`, `.exp-item`, `.hover-fade-*`.
- ESLint uses flat config. `backend/**` is ignored by the root lint; Laravel code is checked with Pint.
- `tsconfig.json` excludes `backend/`. Keep it excluded.

## Verification checklist

```powershell
npm run lint
npx tsc --noEmit
npm run build

$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
cd backend
php artisan test
vendor\bin\pint --test

# optional, when Docker is installed
docker build -t portfolio-api .
```

After a backend deploy, verify `https://api.<domain>/up` and
`https://api.<domain>/api/health`. During the transition, if content data changes, confirm
`src/data/portfolio.json` and `public/images/projects/*` are committed. If only frontend code
changes, do not run the export unnecessarily.

## Gotchas

- `backend/.env` and the local SQLite database are git-ignored. `backend/.env.example`,
  `backend/.env.production.example`, and the root `.env.example` **are** committed.
- Use `127.0.0.1`, not `localhost`, for local backend commands/URLs on Windows.
- If the local admin shows missing images after a fresh clone, run `php artisan storage:link` in
  `backend/`.
- The Render filesystem is ephemeral. Uploaded files disappear on restart until the GitHub uploader
  ships; `storage:link` at startup only makes legacy uploads temporarily browsable.
- Render Free spins down after 15 minutes; the first request can take about a minute. UptimeRobot
  can ping `/up` every 5 minutes.
- Neon may suspend compute when idle. Use the **direct** connection string and `DB_SSLMODE=require`.
- `APP_KEY` must be a valid `base64:` value. Changing it invalidates sessions and encrypted data.
- Render routes to port `10000`; `NGINX_HTTP_PORT=10000` and `SSL_MODE=off` are required for the
  `serversideup/php` image.
- `public/images/projects` is still committed because Vercel builds from git, not from the local
  database.
- Deleting a project/experience in Filament does not delete its old image from the frontend repo;
  remove orphaned files manually if needed.
- The site was migrated from Contentful. The old Contentful env vars and scripts are gone; do not
  restore them.

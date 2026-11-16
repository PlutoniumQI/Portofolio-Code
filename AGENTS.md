<!-- BEGIN:nextjs-agent-rules -->
# This is NOT the Next.js you know

This version has breaking changes — APIs, conventions, and file structure may all differ from your training data. Read the relevant guide in `node_modules/next/dist/docs/` before writing any code. Heed deprecation notices.
<!-- END:nextjs-agent-rules -->

# Portfolio project guide

## Architecture

This repo contains two apps:

- **`/` (Next.js 16, App Router)** — the public bilingual site. Deployed to Vercel Hobby at the
  user's domain. Routes are locale-prefixed (`/id/...`, `/en/...`); `/` redirects to `/id`.
- **`backend/` (Laravel 13 + Filament 5)** — the admin and public API. Local development uses SQLite
  and the public disk; production runs on **Render Free (Docker)** at `api.<domain>`, with **Neon
  Postgres** and a public **GitHub assets repository**.

Content flow:

```text
Filament edit -> Neon Postgres -> GET /api/portfolio -> Next.js ISR -> Vercel
uploads       -> GitHub assets repo -> raw.githubusercontent.com URLs
Publish       -> POST /api/revalidate on Vercel
```

The API-backed frontend and the phase 2 backend are complete. `src/app/page.tsx`,
`src/lib/portfolio.ts`, `src/components/ClientEffects.tsx`, and `src/data/portfolio.json` belong to
the retired one-page app. Do not restore them or the Contentful integration.

## Infrastructure files

- `render.yaml` — Render Blueprint for `portfolio-api` (Docker, Free plan, health check `/up`).
- `backend/Dockerfile` — `serversideup/php:8.4-fpm-nginx`; installs `intl` and `pdo_pgsql`.
- `backend/docker/entrypoint.d/` — startup scripts: config/view cache, Filament assets, opt-in
  migrations and seeding.
- `backend/config/cors.php` — allows only `FRONTEND_ALLOWED_ORIGINS`.
- `backend/config/portfolio.php` — export paths, GitHub upload settings, frontend revalidation URL
  and secret.
- `backend/routes/api.php` — public API: `health`, `portfolio`, `projects`, `posts`, `guestbook`,
  `messages`.
- `backend/.env.production.example` — production env reference. Real secrets live only in Render.
- `.env.example` — frontend env reference: `NEXT_PUBLIC_SITE_URL`, `API_URL`,
  `FRONTEND_REVALIDATE_SECRET`, `PORTFOLIO_REVALIDATE_SECONDS`.
- `docs/deploy-render-neon.md` — full runbook for Neon, the GitHub token, Render, Vercel, DNS,
  keep-alive, security, and troubleshooting.

## Source of truth and generated files

- Edit all content in the Filament admin (`http://127.0.0.1:8000/admin` locally,
  `https://api.<domain>/admin` in production), never in JSON.
- `src/data/portfolio-fallback.json` is **generated** by `php artisan portfolio:fallback`
  (`npm run fallback`). It is a bilingual snapshot used during `next build` and when the API is
  unreachable. Do not hand-edit it; regenerate it after changing seed content.
- `src/data/portfolio.json` and `php artisan portfolio:export` are the retired static pipeline. The
  site does not read them. Do not extend that path.
- UI copy (navigation, section headings, form labels, error messages, meta descriptions) lives in
  `src/lib/dictionaries.ts` for both locales. UI text must never be hardcoded in a component.
- If a content field changes, update all affected layers together:
  1. `backend/database/migrations/*`
  2. `backend/app/Models/*`
  3. `backend/app/Filament/Resources/.../Schemas/*Form.php`
  4. `backend/app/Filament/Resources/.../Tables/*Table.php`
  5. `backend/app/Services/PortfolioService.php` (API payload)
  6. `backend/app/Console/Commands/ExportPortfolioFallback.php` output stays compatible
  7. `src/lib/types.ts`
  8. any component that reads the field, plus `src/lib/dictionaries.ts` if labels change
  9. regenerate `src/data/portfolio-fallback.json`

## Commands

Run frontend commands from the repo root:

```powershell
npm run dev          # Next.js dev server at http://localhost:3000
npm run lint
npx tsc --noEmit
npm run build
npm run start        # serve the production build
npm run dev:backend  # PHP admin at http://127.0.0.1:8000/admin
npm run fallback     # regenerate src/data/portfolio-fallback.json
npm run export       # legacy static JSON; unused by the site
```

Run backend commands from `backend/`:

```powershell
php artisan test
vendor\bin\pint --test
php artisan migrate --seed
php artisan route:list --path=api
php artisan portfolio:fallback
```

Optional Docker build check (requires Docker Desktop):

```powershell
docker build -t portfolio-api .\backend
```

**PHP caveat:** all backend commands need `ext-intl`; the default `php` on this Windows machine
(Herd Lite) does not have it. `npm run dev:backend`, `npm run export`, and `npm run fallback` already
point at the full Herd build. For direct commands, prefix PATH:

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
- CORS is restricted to `FRONTEND_ALLOWED_ORIGINS`. Do not use `*` in production. The Next.js form
  proxy means browsers do not need CORS, but direct calls still do.
- Project statuses: `draft` (not exposed by the API), `published`, `coming_soon` (exposed with a
  badge). Inactive experiences are not exposed.
- `sort_order` is the display order and is drag-reorderable in the Filament tables.
- **Settings → Publish changes** calls the revalidator (`FrontendRevalidator`), which posts to
  `FRONTEND_REVALIDATE_URL` with `FRONTEND_REVALIDATE_SECRET`. Only the legacy section on that page
  uses `portfolio:export`.
- Guestbook entries are hidden from `GET /api/guestbook` until `is_approved` is true. Approve them
  from **Messages & guestbook**.
- `POST /api/messages` is throttled to 5 requests per minute per IP and has a honeypot. Do not remove
  either.
- Do not add a Vercel build for `backend/` or PHP hosting on Vercel. The backend runs on Render as a
  Docker service.

## Image rules

- Uploads go to the public `portfolio-assets` GitHub repo via the Contents API, using a fine-grained
  token (`GITHUB_TOKEN`, Contents: Read and write) limited to that repo. The token is never
  committed. The uploader stores the returned `raw.githubusercontent.com` URL.
- Local development: when `GITHUB_TOKEN` is empty, uploads fall back to Laravel's `public` disk
  (`backend/storage/app/public`, linked with `php artisan storage:link`).
- Upload wins if both the local upload and the external URL are set.
- Manually pasted URLs must be **raw**
  (`https://raw.githubusercontent.com/<user>/<repo>/<branch>/<path>`), **not**
  `github.com/.../blob/...` pages. Private repos will not render for visitors.
- Upload size is capped by `PORTFOLIO_MAX_UPLOAD_KB` (10 MB in production, 2 MB local). Keep
  `PHP_UPLOAD_MAX_FILE_SIZE` and `PHP_POST_MAX_SIZE` above it.
- GitHub's Contents API accepts files up to 100 MB, but images should be compressed and kept much
  smaller. Keep the assets repo well under 1 GB.
- This PHP build has no JPEG/WebP GD support and no Imagick. Do not add server-side image conversion.
- Keep plain `<img>`; no `next/image` remote domains are configured. New remote hosts work without
  config.

## Frontend invariants

- `src/app/[locale]/layout.tsx` is the root layout. There is no `src/app/layout.tsx` or
  `src/app/page.tsx`; `/` is handled by redirects in `next.config.ts`.
- `src/lib/api.ts` fetches `GET /api/portfolio` with ISR tags. During `next build`
  (`NEXT_PHASE === 'phase-production-build'`) it returns `portfolio-fallback.json` on purpose, so
  Vercel builds never depend on Render being awake. Do not remove that branch or fetch the API at
  build time.
- Do not add `loading.tsx` under `[locale]` again. Streaming flushes a 200 shell before `notFound()`
  runs, so unknown project/blog slugs would return HTTP 200. `notFound()` is called in
  `generateMetadata` and the page; that depends on there being no streaming boundary.
- Links and route params are locale-aware. Route `params` are `Promise<...>` in Next 16 and must be
  awaited. Use `Link` from `next/link`, `notFound()` from `next/navigation`.
- `src/app/globals.css` uses Tailwind v4: `@import "tailwindcss"` + `@theme inline`. There is no
  `tailwind.config.*`; custom keyframes/utilities live in that CSS file.
- Theme is class-based (`next-themes`-style inline script in the layout, `.dark` on `<html>`). Colors
  come from tokens (`--ink`, `--muted`, `--surface`, `--line`, `--accent`, `--on-accent`, ...).
  Accent-backed surfaces must use `text-on-accent` / `var(--on-accent)`, never a hardcoded white or
  dark green; `--on-accent` flips between light and dark themes.
- Keep the semantic classes and IDs that styles and scripts query: `#backToTop`, `#navbar`, `#glow`,
  `.reveal`, `.glass-card`, `.gradient-border-ring`, `.btn-shimmer`, `.chip`, `.project-card-glow`,
  `.exp-item`, `.hamburger-line`, `.nav-link`.
- Forms post to the same-origin `POST /api/messages` route (`src/lib/messages.ts`), which forwards to
  `API_URL` server-side. Never call the Laravel API directly from a client component; there is no
  public API URL in the browser and CORS is not part of the form flow.
- `POST /api/revalidate` requires `X-Revalidate-Secret` to equal `FRONTEND_REVALIDATE_SECRET` and
  validates the requested paths. Keep the allow-list; do not revalidate arbitrary paths.
- All copy must exist in both `id` and `en` in `src/lib/dictionaries.ts`; TypeScript enforces the
  shape. English content fields fall back to Indonesian in the API, but UI copy must be translated.
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

After a production build, verify with `npm run start`:

- `/id` and `/en` return 200 with the correct language.
- `/id/projects/<slug>` and `/id/blog/<slug>` return 200 for seeded content.
- Unknown project/blog slugs return **404**, not 200.
- `/` returns a 307 redirect to `/id`; `/fr` returns 404.
- Contact and guestbook posts succeed through `http://127.0.0.1:3000/api/messages`.
- `POST /api/revalidate` returns 401 without the secret and 200 with it.

After a backend deploy, verify `https://api.<domain>/up` and `https://api.<domain>/api/health`.
Regenerate and commit `src/data/portfolio-fallback.json` whenever seed content changes; do not run
the legacy export for it.

## Gotchas

- `backend/.env` and the local SQLite database are git-ignored. `backend/.env.example`,
  `backend/.env.production.example`, and the root `.env.example` **are** committed.
- Use `127.0.0.1`, not `localhost`, for local backend commands/URLs on Windows.
- If the local admin shows missing images after a fresh clone, run `php artisan storage:link` in
  `backend/`.
- The Render filesystem is ephemeral. Only GitHub uploads persist; local-disk fallback uploads
  disappear on restart.
- Render Free spins down after 15 minutes; the first request can take about a minute. UptimeRobot
  can ping `/up` every 5 minutes.
- Neon may suspend compute when idle. Use the **direct** connection string and `DB_SSLMODE=require`.
- `APP_KEY` must be a valid `base64:` value. Changing it invalidates sessions and encrypted data.
- Render routes to port `10000`; `NGINX_HTTP_PORT=10000` and `SSL_MODE=off` are required for the
  `serversideup/php` image.
- Deleting a project/experience in Filament does not delete its image from the GitHub assets repo;
  remove orphaned files manually if needed.
- Next 16: `revalidateTag(tag, 'max')` takes two arguments. If stale `.next/types` errors appear,
  delete `.next` before `tsc`.
- PowerShell treats `src\app\[locale]\...` as a wildcard; use `-LiteralPath` (or quote with care).
- `Invoke-WebRequest` needs `-UseBasicParsing` and throws on 404; wrap it in `try/catch` when
  checking error pages.

# re:SKY — Portfolio

Personal portfolio for Muhammad Resky Ramadhan. Next.js 16 frontend, Laravel 13 + Filament 5 admin,
deployed for free on Vercel + Render + Neon.

> **Status:** phase 1 infrastructure is in place. The public site is still the existing one-page
> Next.js app that reads generated JSON. Phase 2 replaces that with the API, bilingual multi-page
> frontend, GitHub uploads, and the full UKK feature set.

## Stack

| Layer | Technology | Host |
|---|---|---|
| Frontend | Next.js 16 (App Router), Tailwind CSS v4 | Vercel Hobby |
| Admin + API | Laravel 13, Filament 5 | Render Free (Docker) |
| Database | PostgreSQL | Neon Free |
| Images / PDFs | Public GitHub assets repo | GitHub raw URLs |

Target content flow:

```text
Filament edit -> Neon -> GET /api/portfolio -> Next.js ISR -> Vercel
uploads       -> GitHub assets repo -> raw.githubusercontent.com URLs
Publish       -> POST /api/revalidate -> Vercel
```

Until phase 2, content still flows through the legacy export:

```text
Filament -> SQLite -> php artisan portfolio:export -> src/data/portfolio.json -> git push -> Vercel
```

## Project layout

```text
.
├── src/                       Next.js app, components, generated portfolio data
├── public/                    static assets and generated project images
├── backend/                   Laravel 13 + Filament 5
│   ├── Dockerfile             Render production image
│   ├── docker/entrypoint.d/   startup scripts
│   └── config/portfolio.php   export, GitHub, and frontend settings
├── render.yaml                Render Blueprint
├── docs/deploy-render-neon.md deployment runbook
└── AGENTS.md                  agent-facing project rules
```

## Requirements

- Node.js 20+ and npm
- PHP 8.4+ with `ext-intl` and Composer
- On this Windows machine, Composer must use the full Herd PHP build:

  ```powershell
  $env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
  ```

  `npm run dev:backend` and `npm run export` already use that binary automatically.

## Frontend

```powershell
npm install
npm run dev
```

Open <http://localhost:3000>. The current page imports `src/data/portfolio.json` at build time.
Copy `.env.example` to `.env.local` only if you need to override the API URL while developing the
next phase.

## Backend (local admin)

First-time setup:

```powershell
cd backend
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
New-Item -ItemType File -Force database\database.sqlite
php artisan migrate --seed
php artisan storage:link
```

Start the admin:

```powershell
# from the repo root
npm run dev:backend
```

Open <http://127.0.0.1:8000/admin>. The seeded login comes from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in
`backend/.env` (defaults: `admin@portfolio.local` / `password`). After signing in, open the user menu →
**Profile** to change the password.

### Editing content (legacy workflow)

1. Edit **Projects** or **Experience** in `/admin`.
2. Choose **Publish to site** in the sidebar (or run `npm run export`).
3. Commit and push the generated files:

   ```powershell
   git add src/data/portfolio.json public/images/projects
   git commit -m "content: update portfolio"
   git push
   ```

Vercel rebuilds from the committed JSON and images. Projects with status `Draft` are not exported.
`Published` and `Coming Soon` are both exported; the frontend shows the “Coming Soon” badge for the
latter. Inactive experiences are skipped.

### Images

| Option | Field | Behavior |
|---|---|---|
| Local upload | **Upload image** | Copied to `public/images/projects/` by the export command |
| GitHub URL | **Image URL** | Passed through untouched |

For GitHub, use a **raw** URL from a public repo:

```text
https://raw.githubusercontent.com/<user>/<repo>/<branch>/<path>/image.webp
```

Not the `github.com/.../blob/...` page, and not a private repo. If both fields are filled, the local
upload wins.

Local uploads are capped at 2 MB by `PORTFOLIO_MAX_UPLOAD_KB` to match the default PHP limit. To
accept larger files, raise both the PHP limits and that value, then restart the server:

```powershell
php -d upload_max_filesize=10M -d post_max_size=12M artisan serve --host=127.0.0.1 --port=8000
```

PHP here has no JPEG/WebP conversion support, so compress images before uploading. PNG, JPEG, WebP,
GIF, and AVIF uploads are accepted as-is.

## API

The backend exposes a small public API under `/api`:

```text
GET /api/health   liveness JSON (no database access)
GET /up           Render health check
```

Phase 2 adds `GET /api/portfolio` and `POST /api/messages`. CORS is restricted to the origins in
`FRONTEND_ALLOWED_ORIGINS`; see `backend/config/cors.php`.

## Deployment

The full zero-budget runbook is in [`docs/deploy-render-neon.md`](docs/deploy-render-neon.md). It
covers:

1. Creating the public `portfolio-assets` GitHub repo and a fine-grained token.
2. Creating the Neon Postgres project.
3. Creating the Render Blueprint (`render.yaml`).
4. Seeding the admin user and content.
5. Adding `api.<domain>` and the Vercel domains.
6. Keeping the free Render service awake with UptimeRobot.
7. Security, local fallback, and troubleshooting.

Both apps deploy from the same repository: Vercel builds the Next.js app at the root, and Render
builds the Docker image from `backend/`.

## Verification

```powershell
# frontend
npm run lint
npx tsc --noEmit
npm run build

# backend
cd backend
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
php artisan test
vendor\bin\pint --test

# optional, when Docker Desktop is installed
docker build -t portfolio-api .
```

## Notes

- `backend/.env` and the SQLite database are local and git-ignored. Only `*.example` files are
  committed.
- Render's filesystem is ephemeral. Uploaded files will not persist until the GitHub uploader ships;
  use raw GitHub URLs in the meantime.
- Render Free sleeps after 15 minutes. The first request after idle can take about a minute.
- Do not add a Vercel build for `backend/` or a PHP runtime on Vercel.

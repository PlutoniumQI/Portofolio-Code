# re:SKY — Portfolio

Bilingual personal portfolio for Muhammad Resky Ramadhan. A Next.js 16 frontend, a Laravel 13 +
Filament 5 admin and API, deployed for free on Vercel + Render + Neon.

> **Status:** the API-backed bilingual frontend is built; lint, type-check, and the production build
> pass. What remains is deployment — Neon, Render, Vercel, and DNS. The runbook is in
> [`docs/deploy-render-neon.md`](docs/deploy-render-neon.md).

## Stack

| Layer | Technology | Host |
|---|---|---|
| Frontend | Next.js 16 (App Router), Tailwind CSS v4 | Vercel Hobby |
| Admin + API | Laravel 13, Filament 5 | Render Free (Docker) |
| Database | PostgreSQL | Neon Free |
| Images / PDFs | Public GitHub assets repo | GitHub raw URLs |

Content flow:

```text
Filament edit -> Neon Postgres -> GET /api/portfolio -> Next.js ISR -> Vercel
uploads       -> GitHub assets repo -> raw.githubusercontent.com URLs
Publish       -> POST /api/revalidate on Vercel
```

Nothing on the site is hardcoded except the UI copy: projects, experience, skills, education,
certificates, testimonials, blog posts, profile, CV, and social links all come from the admin.

## Pages

Routes are locale-prefixed with `id` and `en`. `/` redirects to `/id`; unknown locales return 404.

| Route | Contents |
|---|---|
| `/[locale]` | Hero, featured projects, highlights |
| `/[locale]/about` | Bio, hard/soft skills with levels, education |
| `/[locale]/skills` | Full skill breakdown by category |
| `/[locale]/projects` | Search + category/first-look filters |
| `/[locale]/projects/[slug]` | Gallery, tech stack, process story, links |
| `/[locale]/experience` | Work, organisation, and competition timeline |
| `/[locale]/certificates` | Certificate grid with credential links |
| `/[locale]/testimonials` | Client, mentor, teacher, and peer quotes |
| `/[locale]/blog` | Post list |
| `/[locale]/blog/[slug]` | Post detail |
| `/[locale]/cv` | Digital CV, printable |
| `/[locale]/contact` | Contact form, email, LinkedIn, socials |
| `/[locale]/guestbook` | Approved guestbook entries + submission form |

Light/dark mode, language switching, back-to-top, and reduced-motion support work across all pages.

## Project layout

```text
src/app/[locale]/            localized pages (root layout lives here)
src/app/api/revalidate/      webhook that refreshes ISR tags
src/app/api/messages/        same-origin proxy for contact/guestbook posts
src/components/              site shell, UI primitives, explorers, forms
src/lib/                     API client, dictionaries, types, i18n helpers
src/data/portfolio-fallback.json   committed bilingual snapshot for builds/offline
backend/                     Laravel 13 + Filament 5 admin and API
render.yaml                  Render Blueprint for portfolio-api
docs/deploy-render-neon.md   deployment runbook
```

## Requirements

- Node.js 20+ and npm
- PHP 8.4+ with `ext-intl` and Composer
- On this Windows machine, backend commands need the full Herd PHP build:

  ```powershell
  $env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
  ```

  `npm run dev:backend`, `npm run export`, and `npm run fallback` already use that binary.

## Local development

### Backend (admin + API)

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

Start it from the repo root:

```powershell
npm run dev:backend
```

Admin: <http://127.0.0.1:8000/admin> — the seeded login comes from `ADMIN_EMAIL` /
`ADMIN_PASSWORD` in `backend/.env` (defaults: `admin@portfolio.local` / `password`). Change it from
the user menu → **Profile** after the first sign-in.

### Frontend

```powershell
npm install
npm run dev
```

Open <http://localhost:3000>; it redirects to `/id`. Copy `.env.example` to `.env.local` only if you
need a different API URL or want to test the revalidation webhook.

The frontend reads the API at request time. If the API is asleep or unreachable, pages render from
`src/data/portfolio-fallback.json` instead of failing. Regenerate that snapshot after changing seed
data:

```powershell
npm run fallback
```

`next build` always uses the snapshot, so Vercel builds never depend on the Render free instance
being awake. Live content is fetched on the first revalidation after deployment.

## Editing content

Everything is edited in Filament; no code or redeploy is needed.

- **Projects** — status `draft` / `published` / `coming_soon`, rich process story, gallery, links,
  searchable tags. Drag rows to reorder.
- **Experience** — work, organisation, and competition entries with bilingual descriptions.
- **Blog posts** — Markdown body, cover image, published flag.
- **Skills** — category, level percentage, hard/soft grouping, order.
- **Education, Certificates, Testimonials** — bilingual forms; testimonial type is `client`,
  `mentor`, `teacher`, or `peer`.
- **Profile & CV** — name, roles, bio, contact details, socials, CV summary and download.
- **Messages & guestbook** — contact submissions and guestbook entries. Guestbook entries stay
  hidden until **Approve** is toggled.

Bilingual fields use `_id` / `_en` tabs. English falls back to Indonesian when a translation is
empty.

After editing, open **Settings → Publish changes** and click **Revalidate live site**. In production
that calls `POST /api/revalidate` on Vercel. Locally, leave `FRONTEND_REVALIDATE_URL` and
`FRONTEND_REVALIDATE_SECRET` empty in `backend/.env`; pages refresh on their own ISR schedule
(`PORTFOLIO_REVALIDATE_SECONDS`, default 300).

## Images and files

Uploads from Filament go to the public `portfolio-assets` GitHub repository through the Contents
API, using a fine-grained `GITHUB_TOKEN` (Contents: Read and write, limited to that repo). The
returned `raw.githubusercontent.com` URL is stored on the record. Set `GITHUB_TOKEN` in Render; never
commit it.

Without a token (the local default), uploads fall back to Laravel's `public` disk at
`backend/storage/app/public`.

- **Upload wins** over the external URL field when both are set.
- If you paste a URL manually, use the **raw** form
  `https://raw.githubusercontent.com/<user>/<repo>/<branch>/<path>` — not a `github.com/.../blob/...`
  page, and not a private repo.
- Size cap: `PORTFOLIO_MAX_UPLOAD_KB` (10 MB in production, 2 MB local). Keep
  `PHP_UPLOAD_MAX_FILE_SIZE` and `PHP_POST_MAX_SIZE` above it.
- Compress images before uploading: this PHP build has no GD/Imagick conversion.
- Render's filesystem is ephemeral, so only the GitHub path persists across restarts.

## Forms

Contact and guestbook submissions post to the frontend's own `POST /api/messages` route. That route
forwards to Laravel server-side using `API_URL`, so the browser never needs CORS or a public API
URL. Both forms validate client-side, include a honeypot, and show localized success/error messages.

Guestbook entries are only returned by `GET /api/guestbook` after approval. Messages are throttled
to 5 requests per minute per IP.

## API

```text
GET  /api/health                 liveness (no database access)
GET  /api/portfolio?locale=id|en localized profile, skills, education, certificates,
                                 experience, projects, testimonials, published posts
GET  /api/projects               search with q, filter by category/featured
GET  /api/projects/{slug}        project detail
GET  /api/posts                  published posts only
GET  /api/posts/{slug}           post detail
GET  /api/guestbook              approved guestbook entries only
POST /api/messages               contact/guestbook submission
```

CORS is restricted to `FRONTEND_ALLOWED_ORIGINS` (`backend/config/cors.php`). The Next.js form proxy
does not need it, but keep it correct for the admin and any direct browser calls.

## Deployment

Both apps deploy from this repository: Vercel builds Next.js at the root, Render builds the Docker
image from `backend/`. The full zero-budget runbook is
[`docs/deploy-render-neon.md`](docs/deploy-render-neon.md); it covers:

1. Creating the public `portfolio-assets` GitHub repo and a fine-grained token.
2. Creating the Neon Postgres project.
3. Creating the Render Blueprint (`render.yaml`).
4. Seeding the admin user and content.
5. Setting Vercel env vars and adding the custom domains.
6. Keeping the free Render service awake with UptimeRobot.
7. Security, local fallback, and troubleshooting.

The shared secret must match on both sides: `FRONTEND_REVALIDATE_SECRET` in Render and Vercel.

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
- Render Free sleeps after 15 minutes; the first request can take about a minute.
- Neon may suspend idle compute. Use the direct connection string with `DB_SSLMODE=require`.
- `src/data/portfolio.json` and `php artisan portfolio:export` belong to the retired one-page
  pipeline. The public site no longer reads them; use the admin and the API instead.
- Do not add a Vercel build for `backend/`, a PHP runtime on Vercel, or a queue worker on Render Free.

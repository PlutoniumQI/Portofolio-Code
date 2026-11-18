# Deploy: Vercel frontend + Vercel Containers backend + Neon

This is the zero-budget production runbook for the portfolio. It deploys:

```text
www.resky.web.id       Vercel Hobby (Next.js frontend)
api.resky.web.id       Vercel Containers (Laravel 13, Blade admin)
Postgres               Neon Free
images + CV            public URLs (for example a GitHub repository)
```

The backend API and the Next.js bilingual frontend are implemented. This
document covers deploying them: Neon, the Vercel backend project, the one-off
database seed, Vercel domains, and the frontend environment.

## Cost and limits

| Service | Plan | Important limits |
|---|---|---|
| Vercel | Hobby | Free for personal projects; custom domain and TLS included; container images are the same Hobby Functions and scale to zero |
| Vercel Functions | Hobby | 2 GB / 1 vCPU per instance, 300 s maximum duration, 4.5 MB request body; monthly Fluid compute allowance is shared with the frontend |
| Vercel Container Registry | Hobby | Images are stored per deployment at $0.10/GB; prune old deployments if storage grows |
| Neon | Free | 100 CU-hours/month and a small storage allowance per project; compute can suspend when idle |
| GitHub | Free public repo | Hosts images only; keep the repository well under 1 GB |

Container images are in beta on all Vercel plans, including Hobby. There is no
credit card requirement for Hobby.

The backend runs as a request-driven Function. Instances scale to zero after
about five minutes without traffic, so the first admin request after a long
pause can take a few seconds. This is normal.

## 1. Prepare the media repository

Media is **URL-only**: the admin does not upload files. It stores public URLs
that you paste into the form.

1. Create a **public** GitHub repository for images and the CV PDF, for example
   `portfolio-assets`.
2. Add a README so the repository is not empty.
3. Organise the files however you like, for example:

   ```text
   portfolio/
     projects/
     certificates/
     blog/
     cv/
     profile/
   ```

4. Open a file on GitHub and copy its **raw** URL, not the page URL:

   ```text
   good: https://raw.githubusercontent.com/<user>/<repo>/main/portfolio/projects/cover.webp
   bad:  https://github.com/<user>/<repo>/blob/main/portfolio/projects/cover.webp
   ```

   The `github.com/.../blob/...` page returns HTML, so an `<img>` tag will not
   render it. Compress images before committing them.

Do not put `.env` files, tokens, or private data in this repository.

## 2. Create the Neon database

1. Sign in at <https://neon.tech> and create a project.
2. Name it `portfolio` and choose **AWS ap-southeast-1 (Singapore)**. The
   backend Function runs in Vercel's `sin1` region, so keeping Neon in the same
   region keeps admin queries fast.
3. Open **Connection string** and copy the **direct** connection string (not
   the pooler). It looks like:

   ```text
   postgresql://user:password@ep-xxx.ap-southeast-1.aws.neon.tech/neondb?sslmode=require
   ```

4. URL-encode special characters in the password before using it in `DB_URL`.
5. Keep `sslmode=require`. Neon requires TLS.

Neon Free suspends compute after inactivity. The first query can take a few
seconds; this is normal.

## 3. Generate the Laravel application key

Run locally from the repository root:

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
cd backend
php artisan key:generate --show
```

Copy the whole output, including the `base64:` prefix. Do not change
`APP_KEY` after the database contains encrypted data; changing it invalidates
sessions and encrypted payloads.

## 4. Push the infrastructure files

Before deploying, confirm the working tree contains:

- `backend/Dockerfile.vercel`
- `backend/Caddyfile`
- `backend/vercel.json` (pins `"framework": "container"`)
- `backend/.dockerignore`
- `backend/.env.production.example`
- `docs/deploy-vercel-neon.md`

There is no root Dockerfile. Vercel builds the backend from `backend/` and the
frontend from the repository root.

Check that no secrets are staged:

```powershell
git status --short
```

`backend/.env`, `.env.local`, and other env files are ignored. Only
`*.example` files are committed. Commit and push to `main`.

## 5. Create the backend project on Vercel

1. Open <https://vercel.com/new> and import the same repository.
2. Project name: `portfolio-api`.
3. **Root Directory:** `backend`.
4. Framework preset: Vercel detects `Dockerfile.vercel` and uses the
   **Container** preset. `backend/vercel.json` also sets
   `"framework": "container"`, so the Vite scaffolding in `backend/` can never
   be selected instead. If the dashboard still shows Vite, change it to
   **Container** before deploying.
5. Leave the build and output settings at their defaults. The image is built
   from `backend/Dockerfile.vercel`.
6. Deploy. The first container build takes about 5–10 minutes.

The build installs production Composer dependencies and precompiles Blade
views; the admin CSS/JS is committed under `backend/public/`. It intentionally
does **not** run `config:cache`, because environment variables are injected at
runtime.

### Environment variables

Open **Project → Settings → Environment Variables** and add these for
**Production** (and Preview if you want previews to work):

| Variable | Value |
|---|---|
| `APP_NAME` | `re:SKY API` |
| `APP_ENV` | `production` |
| `APP_KEY` | output of `php artisan key:generate --show` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://api.resky.web.id` |
| `APP_LOCALE` | `en` |
| `APP_FALLBACK_LOCALE` | `en` |
| `APP_FAKER_LOCALE` | `en_US` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL` | `info` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | Neon **direct** connection string |
| `DB_SSLMODE` | `require` |
| `SESSION_DRIVER` | `database` |
| `SESSION_LIFETIME` | `120` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SESSION_SAME_SITE` | `lax` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `sync` |
| `FILESYSTEM_DISK` | `public` |
| `MAIL_MAILER` | `log` |
| `FRONTEND_URL` | `https://www.resky.web.id` |
| `FRONTEND_ALLOWED_ORIGINS` | `https://resky.web.id,https://www.resky.web.id` |
| `FRONTEND_ALLOW_VERCEL_PREVIEWS` | `false` |
| `FRONTEND_REVALIDATE_URL` | `https://www.resky.web.id/api/revalidate` |
| `FRONTEND_REVALIDATE_SECRET` | a long random value; reuse it on the frontend |

Do **not** set `PORT`: Vercel provides it (default `80`), and the Caddyfile
reads it. Do not add `RUN_MIGRATIONS` or `SEED_DATABASE`: there are no startup
scripts, and migrations are a one-off step below.

`ADMIN_EMAIL` / `ADMIN_PASSWORD` do not need to live in Vercel. They are only
read by the seeder when you run it from your workstation.

After adding the variables, redeploy so the runtime receives them.

### Set the Function region

Open **Project → Settings → Functions → Region** and choose **Singapore
(`sin1`)**. This keeps the backend next to Neon and close to visitors in
Indonesia. A single region is the Hobby default.

### Check the default deployment

Before DNS is configured, confirm the deployment URL:

```text
https://<project>.vercel.app/up          -> 200
https://<project>.vercel.app/api/health  -> {"status":"ok", ...}
https://<project>.vercel.app/admin/login -> login page
```

If `/admin/login` renders without CSS, the build did not copy
`backend/public/css` and `backend/public/vendor`; check `.dockerignore` and the
build logs.

## 6. Run migrations and seed once

Migrations are **not** run by the container. Run them from this machine, which
already has `pdo_pgsql`, against Neon. Environment variables override
`backend/.env`, so the local SQLite database is not touched.

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
cd backend
php artisan config:clear

$env:DB_CONNECTION = 'pgsql'
$env:DB_URL = 'postgresql://user:password@ep-xxx.ap-southeast-1.aws.neon.tech/neondb?sslmode=require'
$env:DB_SSLMODE = 'require'
$env:ADMIN_EMAIL = 'admin@resky.web.id'
$env:ADMIN_PASSWORD = '<the generated password>'

php artisan migrate --force --seed
```

Expected output ends with the migrations and
`Database\Seeders\PortfolioSeeder ... DONE`.

The seeder uses `firstOrCreate` for the admin user and `updateOrCreate` for
content. **Run it once.** Running it again overwrites edits made in the admin.

After seeding, close the terminal or clear the variables. `ADMIN_PASSWORD` is
only used when the user is first created; changing it later does not change an
existing password. Use the **Account** page (`/admin/profile`) for that.

## 7. Add the API custom domain

1. Backend project → **Settings → Domains → Add Domain** →
   `api.resky.web.id`.
2. The domain is already on Vercel DNS, so Vercel creates the record. If an
   `api` record exists, replace it. An explicit `api` record takes precedence
   over the `*.resky.web.id` wildcard.
3. Wait for **Valid Configuration** and the certificate to be issued.
4. Set `APP_URL=https://api.resky.web.id` (already in the table) and redeploy
   if it was different.
5. Verify:

   ```text
   https://api.resky.web.id/up
   https://api.resky.web.id/api/health
   https://api.resky.web.id/admin/login
   ```

Add the custom domain before logging into the admin so cookies and redirects
use the final host from the start. `SESSION_DOMAIN` is left unset, so the admin
cookie is host-only for `api.resky.web.id`.

## 8. Deploy the frontend

The frontend project already exists. Add or update these variables for
**Production**:

| Variable | Value |
|---|---|
| `NEXT_PUBLIC_SITE_URL` | `https://www.resky.web.id` |
| `API_URL` | `https://api.resky.web.id` |
| `FRONTEND_REVALIDATE_SECRET` | same value as the backend |
| `PORTFOLIO_REVALIDATE_SECONDS` | `300` (optional) |

`NEXT_PUBLIC_SITE_URL` is baked into the build for canonical URLs, the
sitemap, and Open Graph tags, so redeploy after changing it.

Forms post to the frontend's own `/api/messages` route, which forwards to
`API_URL` server-side. Browsers never call the API directly, so CORS is not
part of the form flow.

For preview deployments, either add each preview origin temporarily or set
`FRONTEND_ALLOW_VERCEL_PREVIEWS=true` on the backend. Leaving it `false` is
safer.

## 9. Local fallback

The backend still runs locally with SQLite and the public disk. This is the
fallback if Vercel is unavailable during the UKK demo.

```powershell
# Terminal 1 — Laravel admin + API
npm run dev:backend

# Terminal 2 — Next.js
npm run dev
```

- Admin: <http://127.0.0.1:8000/admin>
- Frontend: <http://localhost:3000>
- Media: paste the same public URLs; local and production use identical values.

Local data is separate from Neon. If you edit content locally, it will not
appear on the deployed site unless you re-create it in the production admin.

If the deployed frontend cannot reach Vercel, create `.env.local` in the repo
root:

```text
API_URL=http://127.0.0.1:8000
```

Restart `npm run dev`. This override is for local development only; do not set
it in Vercel.

## 10. Security checklist

- `APP_DEBUG=false` in production.
- `APP_KEY` is a real random key; never commit it.
- Admin password is unique and 20+ characters. The login route throttles failed
  attempts, but a strong password is still required.
- HTTPS only. `SESSION_SECURE_COOKIE=true` is set in the environment.
- `FRONTEND_ALLOWED_ORIGINS` lists exact production origins; do not use `*`.
- Never commit `backend/.env`, `.env.local`, or any filled-in production file.
- Keep `APP_URL` equal to the public API origin so redirects and cookies work.
- Neon credentials live only in Vercel's environment variables.
- Run `composer audit` and `npm audit` periodically; update and re-test.
- Back up content before large edits. Neon Free has a limited restore window.
- Media URLs are public by definition; never paste a private or signed URL.
- Container images and the runtime are beta. Pin the base image tag and re-test
  after dependency updates.

## 11. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Build tries to run Vite or `npm run build` | Vercel detected `vite.config.js` | Keep `backend/Dockerfile.vercel`; the `.vercel` suffix forces the Container preset |
| `502` on every route | Caddy is not listening on Vercel's `PORT` | Keep `:{$PORT:80}` in `Caddyfile`; do not override `PORT` |
| `No application encryption key has been specified` | `APP_KEY` missing or malformed | Set the full `base64:...` value and redeploy |
| Admin page errors: `intl` extension required | The image build skipped `intl` | Check `install-php-extensions intl zip pdo_pgsql` in `Dockerfile.vercel` |
| Admin CSS/JS 404 | `backend/public/css` or `backend/public/vendor` missing from the image | Check `.dockerignore`; both directories are committed |
| `SQLSTATE[08006]` / connection timeout | Neon suspended, wrong host, or missing TLS | Use the direct Neon URL, `sslmode=require`, wake the project in Neon |
| `password authentication failed` | Password not URL-encoded | Percent-encode `@`, `:`, `/`, `?`, `#` in `DB_URL` |
| `419 Page Expired` on admin login | Cookie/host mismatch | Clear cookies, set `APP_URL` to the exact HTTPS origin, keep `SESSION_SECURE_COOKIE=true` |
| Admin redirects to `http://` | Proxy headers not trusted | `bootstrap/app.php` trusts proxies; confirm Vercel sends `X-Forwarded-Proto` |
| First admin request is slow | Container scaled to zero after idle | Expected on Hobby; keep the admin tab warm during a demo |
| `504` after about five minutes | Function exceeded the 300 s Hobby limit | Avoid long admin operations |
| `413 FUNCTION_PAYLOAD_TOO_LARGE` | Request body above 4.5 MB | Media is URL-only, so this should not happen; keep form payloads small |
| `SEED_DATABASE` change does nothing | There is no startup seeding on Vercel | Run the one-off `migrate --seed` command from a workstation |
| Content edits do not appear | ISR cache is still warm | Use **Settings → Publish changes → Revalidate live site**, or wait for `PORTFOLIO_REVALIDATE_SECONDS` |
| Broken image on the live site | Pasted a `blob` page URL, or the repository is private | Use the `raw.githubusercontent.com` URL, and keep the repo public |
| Docker build fails during `composer install` | PHP version or missing extension | The lock file requires PHP >= 8.4.1; the image is `frankenphp:1-php8.4-bookworm` |
| Old images fill Container Registry storage | Every deployment pushes an image | Delete old deployments in the Vercel dashboard |

## 12. Content workflow after deploy

Everything is edited in the production admin at
`https://api.resky.web.id/admin`:

1. Create or edit content. Project statuses are `draft` (hidden), `published`,
   and `coming_soon` (visible with a badge).
2. Paste image URLs into the form. Use `raw.githubusercontent.com` links; the
   cover, gallery, certificate, avatar, blog cover, and CV fields all accept a
   public URL.
3. Review **Testimonial submissions**; publish or reject each pending entry.
4. Open **Publish changes → Revalidate live site** to refresh the
   Vercel cache immediately.

Pages also refresh on their own every `PORTFOLIO_REVALIDATE_SECONDS` (default
300). New project and blog slugs are rendered on demand, so they work without a
redeploy.

The committed `src/data/portfolio-fallback.json` is only a build/offline
snapshot. Regenerate it with `npm run fallback` if you want it to match
production content, then commit and push.

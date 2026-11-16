# Deploy: Vercel + Render Free + Neon + GitHub assets

This is the zero-budget production runbook for the portfolio. It deploys:

```text
your-domain.com        Vercel Hobby (Next.js frontend)
api.your-domain.com    Render Free (Laravel 13 + Filament 5, Docker)
Postgres               Neon Free
images + PDFs          public GitHub assets repository
```

The backend API, GitHub uploader, and the Next.js bilingual frontend are
implemented. This document covers deploying them: Neon, Render, the assets
repository, Vercel, and DNS.

## Cost and limits

| Service | Plan | Important limits |
|---|---|---|
| Vercel | Hobby | Free for personal projects; custom domain and TLS included |
| Render | Free web service | 750 instance hours/month, 512 MB RAM, 0.1 CPU, spins down after 15 minutes idle, custom domain and TLS included |
| Neon | Free | 100 CU-hours/month and a small storage allowance per project; compute can suspend when idle |
| GitHub | Free public repo | Keep the assets repo small; the Contents API rejects files over 100 MB |

Render Free can take about a minute to wake after idling. Use UptimeRobot or
cron-job.org to ping `/up` every 5 minutes during the UKK period.

## 1. Create the public assets repository

1. Create a **public** GitHub repository named `portfolio-assets`.
2. Add a README so the repository is not empty.
3. Create these folders (or change `GITHUB_ASSETS_PATH` later):

   ```text
   portfolio/
     projects/
     certificates/
     blog/
     cv/
     profile/
   ```

4. Images must be compressed before uploading. GitHub raw URLs are not an
   image CDN; keep total repository size well under 1 GB.

Do not put `.env` files, tokens, or private data in this repository.

## 2. Create a fine-grained GitHub token

1. GitHub → Settings → Developer settings → Personal access tokens →
   **Fine-grained tokens** → Generate new token.
2. Resource owner: your account.
3. Repository access: **Only select repositories** → `portfolio-assets`.
4. Repository permissions:
   - **Contents: Read and write** (required for uploads)
   - Metadata: Read-only (automatic)
5. Set an expiration you will remember (90 days is a good default).
6. Generate and copy the token once. Never commit it or paste it into chat.

The backend uses this token to commit uploaded images. If the token is empty
locally, uploads fall back to Laravel's public disk.

## 3. Create the Neon database

1. Sign in at <https://neon.tech> and create a project.
2. Name it `portfolio` and choose the region closest to Indonesia
   (AWS `ap-southeast-1`, Singapore).
3. Open **Connection string** and copy the **direct** connection string
   (not the pooler). It looks like:

   ```text
   postgresql://user:password@ep-xxx.ap-southeast-1.aws.neon.tech/neondb?sslmode=require
   ```

4. URL-encode special characters in the password before using it in `DB_URL`.
5. Keep `sslmode=require`. Neon requires TLS.

Neon Free suspends compute after inactivity. The first query can take a few
seconds; this is normal.

## 4. Generate the Laravel application key

Run locally from the repository root:

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
cd backend
php artisan key:generate --show
```

Copy the whole output, including the `base64:` prefix. Do not change
`APP_KEY` after the database contains encrypted data; changing it invalidates
sessions and encrypted payloads.

## 5. Push the infrastructure files

Before deploying, confirm the working tree contains:

- `render.yaml`
- `backend/Dockerfile`
- `backend/.dockerignore`
- `backend/docker/entrypoint.d/*.sh`
- `backend/.env.production.example`

Check that no secrets are staged:

```powershell
git status --short
```

`backend/.env`, `.env.local`, and other env files are ignored. Only
`*.example` files are committed. Commit and push to GitHub.

## 6. Create the Render service

1. Render Dashboard → **New** → **Blueprint**.
2. Connect the GitHub repository that contains this project.
3. Render reads `render.yaml` and shows the `portfolio-api` service.
4. Fill the values marked `sync: false`:

   | Variable | First-deploy value |
   |---|---|
   | `APP_KEY` | output of `php artisan key:generate --show` |
   | `APP_URL` | `https://portfolio-api.onrender.com` (or your custom API domain if DNS is ready) |
   | `DB_URL` | Neon **direct** connection string |
   | `ADMIN_EMAIL` | your admin email |
   | `ADMIN_PASSWORD` | a long random password, 20+ characters |
   | `GITHUB_TOKEN` | fine-grained token from step 2 |
   | `GITHUB_ASSETS_OWNER` | your GitHub username |
   | `GITHUB_ASSETS_REPO` | `portfolio-assets` |
   | `FRONTEND_URL` | `http://localhost:3000` temporarily |
   | `FRONTEND_ALLOWED_ORIGINS` | `http://localhost:3000,http://127.0.0.1:3000` temporarily |
   | `FRONTEND_REVALIDATE_URL` | `https://your-domain.com/api/revalidate` |
   | `FRONTEND_REVALIDATE_SECRET` | a long random value; reuse it on Vercel later |
   | `SEED_DATABASE` | `false` for the first boot |

5. Create the Blueprint and wait for the first deploy.
6. Watch the Render logs for:

   ```text
   Caching Laravel config and views...
   Publishing Filament assets...
   Database migrations...
   ```

7. Confirm the health check passes:
   - `https://portfolio-api.onrender.com/up` → 200
   - `https://portfolio-api.onrender.com/api/health` → JSON with `"status": "ok"`

### Seed the admin user and placeholder content

The seeder is opt-in so restarts never overwrite real content.

1. Render Dashboard → `portfolio-api` → **Environment**.
2. Set `SEED_DATABASE=true` and save. Render restarts the service.
3. Check the logs for `Seeding: Database\Seeders\DatabaseSeeder`.
4. Open `https://portfolio-api.onrender.com/admin` and log in with
   `ADMIN_EMAIL` / `ADMIN_PASSWORD`.
5. Change the password from the Filament profile menu.
6. Set `SEED_DATABASE=false` and save again.

`ADMIN_PASSWORD` is only used when the user is first created. Changing the
environment variable later does not change an existing password.

If the admin user is missing, set `SEED_DATABASE=true` again and check the
deploy logs. Seeders are idempotent, so re-running them is safe.

## 7. Add the API custom domain

1. Render service → **Settings** → **Custom Domains** → add
   `api.your-domain.com`.
2. Render shows a CNAME target such as `portfolio-api.onrender.com`.
3. In your DNS provider, create:

   ```text
   Type   Name   Value
   CNAME  api    portfolio-api.onrender.com
   ```

   If you use Cloudflare, start with **DNS only** (grey cloud) so Render can
   issue the certificate. You can enable the proxy later with SSL/TLS set to
   **Full (strict)**.
4. Wait for Render to show the certificate as active.
5. Change `APP_URL` to `https://api.your-domain.com` and save. Render restarts.
6. Verify:

   ```text
   https://api.your-domain.com/up
   https://api.your-domain.com/api/health
   https://api.your-domain.com/admin
   ```

Add the custom domain before logging into the admin if you want cookies and
redirects to use the final host from the start.

## 8. Deploy the frontend to Vercel

1. Vercel → **Add New** → **Project** → import the same repository.
2. Framework preset: **Next.js**. Root directory: `./`.
3. Build command: `npm run build`. Output: Next.js default.
4. Add these environment variables for **Production** and **Preview**:

   | Variable | Value |
   |---|---|
   | `NEXT_PUBLIC_SITE_URL` | `https://your-domain.com` |
   | `API_URL` | `https://api.your-domain.com` |
   | `FRONTEND_REVALIDATE_SECRET` | same value as Render's `FRONTEND_REVALIDATE_SECRET` |
   | `PORTFOLIO_REVALIDATE_SECONDS` | `300` (optional) |

   `NEXT_PUBLIC_SITE_URL` is the only public variable: it is baked into the
   build for canonical URLs, the sitemap, and Open Graph tags. Forms post to
   the frontend's own `/api/messages` route, which forwards to `API_URL`
   server-side, so no public API URL or CORS setup is needed in the browser.

5. Deploy.
6. Add `your-domain.com` and `www.your-domain.com` in Vercel → Settings →
   Domains. Use the exact DNS records Vercel shows. For an apex domain, Vercel
   usually asks for an `A` record; for `www`, a `CNAME`.
7. After the frontend URL is live, update Render:

   ```text
   FRONTEND_URL=https://your-domain.com
   FRONTEND_ALLOWED_ORIGINS=https://your-domain.com,https://www.your-domain.com
   FRONTEND_REVALIDATE_URL=https://your-domain.com/api/revalidate
   ```

   Save and let Render restart. CORS origins must match exactly: scheme, host,
   and port. No trailing slash.

For Vercel preview deployments, either add each preview origin temporarily or
set `FRONTEND_ALLOW_VERCEL_PREVIEWS=true` on Render. Leaving it `false` is safer.

## 9. Keep the free backend awake

Render Free spins down after 15 minutes without traffic.

1. Create a free UptimeRobot account.
2. Add an **HTTP(s)** monitor:
   - URL: `https://api.your-domain.com/up`
   - Interval: 5 minutes
   - Alert contacts: your email
3. Keep the monitor enabled during the UKK period.

This uses one Render service's 750 monthly instance hours. Do not run a second
free web service in the same workspace unless you have checked the usage page.

Before a presentation, open `https://api.your-domain.com/up` once and wait for
it to return 200. The first request after sleep can take about a minute.

## 10. Local fallback

The backend still runs locally with SQLite and the public disk. This is the
fallback if Render is asleep or unavailable during the UKK demo.

```powershell
# Terminal 1 — Laravel + Filament
npm run dev:backend

# Terminal 2 — Next.js
npm run dev
```

- Admin: <http://127.0.0.1:8000/admin>
- Frontend: <http://localhost:3000>
- Local uploads: `backend/storage/app/public` (run `php artisan storage:link`
  once in `backend/` if images are missing)

Local data is separate from Neon. If you edit content locally, it will not
appear on the deployed site unless you re-create it in the production admin.

If the deployed frontend cannot reach Render, create `.env.local` in the repo
root:

```text
API_URL=http://127.0.0.1:8000
```

Restart `npm run dev`. This override is for local development only; do not set
it in Vercel.

## 11. Security checklist

- `APP_DEBUG=false` in production.
- `APP_KEY` is a real random key; never commit it.
- Admin password is unique and 20+ characters. Filament throttles failed logins
  (5 attempts), but a strong password is still required.
- HTTPS only. `SESSION_SECURE_COOKIE=true` is set in `render.yaml`.
- `FRONTEND_ALLOWED_ORIGINS` lists exact production origins; do not use `*`.
- GitHub token is limited to `portfolio-assets` with **Contents: Read and write**.
  Rotate it before it expires. If it leaks, revoke it immediately.
- Never commit `backend/.env`, `.env.local`, or any filled-in production file.
- Keep `APP_URL` equal to the public API origin so redirects and cookies work.
- Neon credentials and the GitHub token live only in Render's environment.
- Run `composer audit` and `npm audit` periodically; update and re-test.
- Back up content before large edits. Neon Free has a limited restore window;
  the GitHub assets repository holds the images, not the database rows.

## 12. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| First request returns 502/503 for ~1 minute | Render Free cold start | Wait, or keep `/up` pinged by UptimeRobot |
| `No application encryption key has been specified` | `APP_KEY` missing or malformed | Set the full `base64:...` value and restart |
| Filament tables error: `intl` extension required | Docker build did not install `intl` | Check the Docker build logs; `install-php-extensions intl` must run |
| `SQLSTATE[08006]` / connection timeout | Neon suspended, wrong host, or missing TLS | Use the direct Neon URL, `sslmode=require`, wake the project in Neon |
| `password authentication failed` | Password not URL-encoded | Percent-encode `@`, `:`, `/`, `?`, `#` in `DB_URL` |
| Filament CSS/JS 404 | `filament:assets` failed or `public` not writable | Check startup logs; rebuild the image |
| `419 Page Expired` on admin login | Cookie/host mismatch | Clear cookies, set `APP_URL` to the exact HTTPS origin, keep `SESSION_SECURE_COOKIE=true` |
| Admin redirects to `http://` | Proxy headers not trusted | `bootstrap/app.php` trusts proxies; check `X-Forwarded-Proto` reaches Laravel |
| Uploaded image disappears after restart | Render's filesystem is ephemeral and `GITHUB_TOKEN` is missing or invalid | Set a valid token; check the upload error in the admin |
| Render deploy fails during `composer install` | PHP version or missing extension | The lock file requires PHP >= 8.4.1; use the `8.4-fpm-nginx` image |
| Render health check times out | App listens on the wrong port | `NGINX_HTTP_PORT=10000` and `SSL_MODE=off` must be set |
| CORS error in the browser | Origin not listed exactly | Add scheme + host (+ port), no trailing slash; restart Render |
| `SEED_DATABASE` change does nothing | Env var not saved, or seed logs show an error | Save the env var, wait for the restart, inspect logs |
| Free instance hours exhausted | More than one always-on service | Check Render usage; keep only `portfolio-api` awake |

## 13. Content workflow after deploy

Everything is edited in the production admin at `https://api.your-domain.com/admin`:

1. Create or edit content. Project statuses are `draft` (hidden), `published`, and
   `coming_soon` (visible with a badge).
2. Upload images through the form. They are committed to `portfolio-assets` and served
   from `raw.githubusercontent.com` URLs, so they survive Render restarts.
3. Approve guestbook entries in **Messages & guestbook**.
4. Open **Settings → Publish changes → Revalidate live site** to refresh the Vercel
   cache immediately.

Pages also refresh on their own every `PORTFOLIO_REVALIDATE_SECONDS` (default 300).
New project and blog slugs are rendered on demand, so they work without a redeploy.

The committed `src/data/portfolio-fallback.json` is only a build/offline snapshot.
Regenerate it with `npm run fallback` if you want it to match production content, then
commit and push.

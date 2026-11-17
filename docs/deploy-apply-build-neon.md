# Deploy: Vercel + Apply.Build + Neon

This is the zero-budget production runbook for the portfolio. It deploys:

```text
your-domain.com        Vercel Hobby (Next.js frontend)
api.your-domain.com    Apply.Build Free (Laravel 13 + Filament 5, Docker)
Postgres               Neon Free
images + CV            public URLs (for example a GitHub repository)
```

The backend API and the Next.js bilingual frontend are implemented. This
document covers deploying them: Neon, Apply.Build, Vercel, and DNS.

## Cost and limits

| Service | Plan | Important limits |
|---|---|---|
| Vercel | Hobby | Free for personal projects; custom domain and TLS included |
| Apply.Build | Free | 0.5 vCPU, 512 MiB RAM, 5 GB disk, 1 MiB request body, 60 s request timeout, custom domain and TLS included, no credit card |
| Neon | Free | 100 CU-hours/month and a small storage allowance per project; compute can suspend when idle |
| GitHub | Free public repo | Hosts images only; keep the repository well under 1 GB |

Apply.Build runs a persistent container, so there is no cold start and no
keep-alive monitor to configure. The free instance is small: keep the PHP-FPM
pool at three workers and avoid large form payloads.

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
2. Name it `portfolio` and choose a region close to the backend. Apply.Build
   hosts in the EU, so AWS `eu-central-1` (Frankfurt) keeps admin queries fast.
   Singapore (`ap-southeast-1`) also works, with higher latency from the EU.
3. Open **Connection string** and copy the **direct** connection string
   (not the pooler). It looks like:

   ```text
   postgresql://user:password@ep-xxx.eu-central-1.aws.neon.tech/neondb?sslmode=require
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

- `Dockerfile` at the repository root (Apply.Build builds from the root)
- `.dockerignore` at the repository root
- `backend/docker/entrypoint.d/*.sh`
- `backend/.env.production.example`

Check that no secrets are staged:

```powershell
git status --short
```

`backend/.env`, `.env.local`, and other env files are ignored. Only
`*.example` files are committed. Commit and push to `main`.

## 5. Create the Apply.Build app

1. Sign in at <https://apply.build>.
2. **Connections** → install the Apply.Build GitHub App and grant it access to
   this repository.
3. **Apps** → **Deploy App** → select the repository and the `main` branch.
4. Build mode: **Dockerfile**. Apply.Build finds the root `Dockerfile`.
5. App size: **Small** (0.5 vCPU / 512 MiB). This is the free package.
6. Port: **8080**. Health check path: **`/up`**.
7. Confirm the app. The first deployment starts automatically.
8. Open **Settings → Environment Variables** and add the values below, then
   redeploy (**Redeploy** in the app view). Environment changes only take
   effect on the next deployment.

| Variable | Value |
|---|---|
| `APP_NAME` | `re:SKY API` |
| `APP_ENV` | `production` |
| `APP_KEY` | output of `php artisan key:generate --show` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://api.your-domain.com` (or the `*.apps.apply.build` URL until DNS is ready) |
| `APP_LOCALE` | `en` |
| `APP_FALLBACK_LOCALE` | `en` |
| `APP_FAKER_LOCALE` | `en_US` |
| `LOG_CHANNEL` | `stderr` |
| `LOG_LEVEL` | `info` |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | Neon **direct** connection string |
| `DB_SSLMODE` | `require` |
| `SESSION_DRIVER` | `database` |
| `SESSION_SECURE_COOKIE` | `true` |
| `SESSION_SAME_SITE` | `lax` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `sync` |
| `FILESYSTEM_DISK` | `public` |
| `RUN_MIGRATIONS` | `true` |
| `SEED_DATABASE` | `true` for the first boot only |
| `ADMIN_EMAIL` | your admin email |
| `ADMIN_PASSWORD` | a long random password, 20+ characters |
| `FRONTEND_URL` | `https://your-domain.com` |
| `FRONTEND_ALLOWED_ORIGINS` | `https://your-domain.com,https://www.your-domain.com` |
| `FRONTEND_ALLOW_VERCEL_PREVIEWS` | `false` |
| `FRONTEND_REVALIDATE_URL` | `https://your-domain.com/api/revalidate` |
| `FRONTEND_REVALIDATE_SECRET` | a long random value; reuse it on Vercel later |
| `NGINX_HTTP_PORT` | `8080` |
| `SSL_MODE` | `off` |
| `HEALTHCHECK_PATH` | `/up` |
| `PHP_OPCACHE_ENABLE` | `1` |
| `PHP_MEMORY_LIMIT` | `192M` |
| `PHP_FPM_PM_CONTROL` | `dynamic` |
| `PHP_FPM_PM_MAX_CHILDREN` | `3` |
| `PHP_FPM_PM_START_SERVERS` | `1` |
| `PHP_FPM_PM_MIN_SPARE_SERVERS` | `1` |
| `PHP_FPM_PM_MAX_SPARE_SERVERS` | `2` |

Watch the build logs. A successful boot prints:

```text
Caching Laravel config and views...
Publishing Filament assets...
Database migrations...
```

Before DNS is configured, confirm health on the default host:

```text
https://<app>.apps.apply.build/up          -> 200
https://<app>.apps.apply.build/api/health  -> {"status":"ok", ...}
```

### Seed the admin user and placeholder content

The seeder is opt-in so restarts never overwrite real content.

1. Keep `SEED_DATABASE=true` for the first deployment and check the logs for
   `Seeding: Database\Seeders\DatabaseSeeder`.
2. Open `https://<app>.apps.apply.build/admin` and log in with
   `ADMIN_EMAIL` / `ADMIN_PASSWORD`.
3. Change the password from the Filament profile menu.
4. Set `SEED_DATABASE=false` and redeploy. Do not leave it `true`: seeders use
   `updateOrCreate`, so every restart would overwrite your edits.

`ADMIN_PASSWORD` is only used when the user is first created. Changing the
environment variable later does not change an existing password.

## 6. Add the API custom domain

1. Apply.Build app → **Settings** → **Domains** → **Add Domain** →
   `api.your-domain.com`.
2. Apply.Build shows a CNAME target such as
   `your-app.apps.apply.build`.
3. In your DNS provider, create an explicit record. If a wildcard already
   points elsewhere, the exact `api` record takes precedence:

   ```text
   Type   Name   Value
   CNAME  api    your-app.apps.apply.build
   ```

4. Wait for Apply.Build to verify the domain and issue the certificate. This
   is usually minutes; DNS propagation can take longer.
5. Set `APP_URL=https://api.your-domain.com` and redeploy.
6. Verify:

   ```text
   https://api.your-domain.com/up
   https://api.your-domain.com/api/health
   https://api.your-domain.com/admin/login
   ```

Add the custom domain before logging into the admin if you want cookies and
redirects to use the final host from the start.

## 7. Deploy the frontend to Vercel

1. Vercel → **Add New** → **Project** → import the same repository.
2. Framework preset: **Next.js**. Root directory: `./`.
3. Build command: `npm run build`. Output: Next.js default.
4. Add these environment variables for **Production** and **Preview**:

   | Variable | Value |
   |---|---|
   | `NEXT_PUBLIC_SITE_URL` | `https://www.your-domain.com` |
   | `API_URL` | `https://api.your-domain.com` |
   | `FRONTEND_REVALIDATE_SECRET` | same value as the Apply.Build backend |
   | `PORTFOLIO_REVALIDATE_SECONDS` | `300` (optional) |

   `NEXT_PUBLIC_SITE_URL` is the only public variable: it is baked into the
   build for canonical URLs, the sitemap, and Open Graph tags. Forms post to
   the frontend's own `/api/messages` route, which forwards to `API_URL`
   server-side, so no public API URL or CORS setup is needed in the browser.

5. Deploy.
6. Add `your-domain.com` and `www.your-domain.com` in Vercel → Settings →
   Domains. Use the exact DNS records Vercel shows. For an apex domain, Vercel
   usually asks for an `A` record; for `www`, a `CNAME`.

For Vercel preview deployments, either add each preview origin temporarily or
set `FRONTEND_ALLOW_VERCEL_PREVIEWS=true` on Apply.Build. Leaving it `false`
is safer.

## 8. Local fallback

The backend still runs locally with SQLite and the public disk. This is the
fallback if Apply.Build is unavailable during the UKK demo.

```powershell
# Terminal 1 — Laravel + Filament
npm run dev:backend

# Terminal 2 — Next.js
npm run dev
```

- Admin: <http://127.0.0.1:8000/admin>
- Frontend: <http://localhost:3000>
- Media: paste the same public URLs; local and production use identical values.

Local data is separate from Neon. If you edit content locally, it will not
appear on the deployed site unless you re-create it in the production admin.

If the deployed frontend cannot reach Apply.Build, create `.env.local` in the
repo root:

```text
API_URL=http://127.0.0.1:8000
```

Restart `npm run dev`. This override is for local development only; do not set
it in Vercel.

## 9. Security checklist

- `APP_DEBUG=false` in production.
- `APP_KEY` is a real random key; never commit it.
- Admin password is unique and 20+ characters. Filament throttles failed logins
  (5 attempts), but a strong password is still required.
- HTTPS only. `SESSION_SECURE_COOKIE=true` is set in the environment.
- `FRONTEND_ALLOWED_ORIGINS` lists exact production origins; do not use `*`.
- Never commit `backend/.env`, `.env.local`, or any filled-in production file.
- Keep `APP_URL` equal to the public API origin so redirects and cookies work.
- Neon credentials live only in Apply.Build's environment.
- Run `composer audit` and `npm audit` periodically; update and re-test.
- Back up content before large edits. Neon Free has a limited restore window.
- Media URLs are public by definition; never paste a private or signed URL.

## 10. Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| Health check flips to **Error** | Wrong port or health path | Port `8080`, `NGINX_HTTP_PORT=8080`, health path `/up`, `SSL_MODE=off` |
| `No application encryption key has been specified` | `APP_KEY` missing or malformed | Set the full `base64:...` value and redeploy |
| Filament tables error: `intl` extension required | Docker build did not install `intl` | Check the build logs; the root `Dockerfile` runs `install-php-extensions intl pdo_pgsql` |
| `SQLSTATE[08006]` / connection timeout | Neon suspended, wrong host, or missing TLS | Use the direct Neon URL, `sslmode=require`, wake the project in Neon |
| `password authentication failed` | Password not URL-encoded | Percent-encode `@`, `:`, `/`, `?`, `#` in `DB_URL` |
| Filament CSS/JS 404 | `filament:assets` failed or `public` not writable | Check startup logs; rebuild the image |
| `419 Page Expired` on admin login | Cookie/host mismatch | Clear cookies, set `APP_URL` to the exact HTTPS origin, keep `SESSION_SECURE_COOKIE=true` |
| Admin redirects to `http://` | Proxy headers not trusted | `bootstrap/app.php` trusts proxies; check `X-Forwarded-Proto` reaches Laravel |
| `413 Request Entity Too Large` | Request body above Apply.Build's 1 MiB limit | Media is URL-only, so this should not happen; keep form payloads small |
| `504` after about a minute | Request exceeded the 60 s timeout | Avoid long admin operations; split imports or retries |
| App returns 502 under load | PHP-FPM exhausted the 512 MiB instance | Keep `PHP_FPM_PM_MAX_CHILDREN=3` and `PHP_MEMORY_LIMIT=192M` |
| Broken image on the live site | Pasted a `blob` page URL, or the repository is private | Use the `raw.githubusercontent.com` URL, and keep the repo public |
| Docker build fails during `composer install` | PHP version or missing extension | The lock file requires PHP >= 8.4.1; the image is `8.4-fpm-nginx` |
| CORS error in the browser | Origin not listed exactly | Add scheme + host (+ port), no trailing slash; redeploy |
| `SEED_DATABASE` change does nothing | Env var saved but not redeployed | Redeploy after every environment change |

## 11. Content workflow after deploy

Everything is edited in the production admin at
`https://api.your-domain.com/admin`:

1. Create or edit content. Project statuses are `draft` (hidden), `published`,
   and `coming_soon` (visible with a badge).
2. Paste image URLs into the form. Use `raw.githubusercontent.com` links; the
   cover, gallery, certificate, avatar, blog cover, and CV fields all accept a
   public URL.
3. Approve guestbook entries in **Messages & guestbook**.
4. Open **Settings → Publish changes → Revalidate live site** to refresh the
   Vercel cache immediately.

Pages also refresh on their own every `PORTFOLIO_REVALIDATE_SECONDS` (default
300). New project and blog slugs are rendered on demand, so they work without a
redeploy.

The committed `src/data/portfolio-fallback.json` is only a build/offline
snapshot. Regenerate it with `npm run fallback` if you want it to match
production content, then commit and push.

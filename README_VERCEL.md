# Deploying Laravel 12 + Filament + TiDB to Vercel

This guide provides step-by-step instructions for deploying this application onto Vercel and connecting it to a TiDB Serverless MySQL database.

---

## 1. Vercel Dashboard Environment Variables

Go to your Vercel Dashboard > **Project Settings** > **Environment Variables**, and add the following keys:

| Environment Variable | Value | Description |
| :--- | :--- | :--- |
| `APP_KEY` | `base64:...` | Run `php artisan key:generate --show` locally to get a valid key. |
| `APP_ENV` | `production` | Environment mode. |
| `APP_DEBUG` | `false` | Disable debug mode for security. |
| `APP_URL` | `https://your-domain.vercel.app` | Your Vercel domain or custom domain. |
| `DB_CONNECTION` | `mysql` | Driver for TiDB. |
| `DB_HOST` | `gateway01.us-east-1.prod.aws.tidbcloud.com` | Replace with your actual TiDB serverless host. |
| `DB_PORT` | `4000` | TiDB serverless default port. |
| `DB_DATABASE` | `your_database_name` | The name of your TiDB database. |
| `DB_USERNAME` | `your_username.root` | Your TiDB username. |
| `DB_PASSWORD` | `your_tidb_password` | Your TiDB database password. |
| `MYSQL_ATTR_SSL_CA` | `/etc/ssl/certs/ca-certificates.crt` | Path to the Linux root CA certificates bundle for SSL. |
| `PUBLIC_DISK_DRIVER` | `s3` | Set to `s3` in production to route all public files to Supabase. |
| `SUPABASE_ACCESS_KEY_ID` | `your_access_key_id` | Obtained from Supabase Dashboard > Storage > S3 Configuration. |
| `SUPABASE_SECRET_ACCESS_KEY` | `your_secret_access_key` | Obtained from Supabase Dashboard > Storage > S3 Configuration. |
| `SUPABASE_DEFAULT_REGION` | `us-east-1` | The region of your Supabase project (e.g., `us-east-1`, `ap-southeast-1`). |
| `SUPABASE_BUCKET` | `your_bucket_name` | The name of the bucket you created in Supabase Storage. |
| `SUPABASE_ENDPOINT` | `https://<ref>.storage.supabase.co/storage/v1/s3` | Your Supabase S3 API Endpoint. |
| `SUPABASE_USE_PATH_STYLE_ENDPOINT` | `true` | Required for Supabase S3 connection. |

### Note on Vercel Read-Only File System
Vercel's serverless environment has a read-only filesystem. The configuration, views, events, and packages caches are handled in `vercel.json` to automatically write to the `/tmp` directory. 

If you prefer to configure those in the Vercel Dashboard, add:
* `APP_CONFIG_CACHE` = `/tmp/config.php`
* `APP_EVENTS_CACHE` = `/tmp/events.php`
* `APP_PACKAGES_CACHE` = `/tmp/packages.php`
* `APP_ROUTES_CACHE` = `/tmp/routes.php`
* `VIEW_COMPILED_PATH` = `/tmp`
* `SESSION_DRIVER` = `cookie`
* `CACHE_STORE` = `array`
* `CACHE_DRIVER` = `array`
* `LOG_CHANNEL` = `stderr`

---

## 2. Filament Assets & Vercel Build Step

Because Vercel is a serverless environment, the vendor assets (like Filament's CSS/JS) must be published to the `public/` directory during Vercel's build process.

To do this, update the `scripts` section of your `package.json` file to run `php artisan filament:upgrade` as part of your build pipeline:

```json
"scripts": {
    "dev": "vite",
    "build": "vite build && php artisan filament:upgrade"
}
```

By adding `php artisan filament:upgrade` to your `build` script:
1. Vercel will install PHP and Composer dependencies first.
2. Vercel will run `npm run build` which triggers Vite compilation.
3. Filament will copy its vendor CSS/JS assets directly into `public/vendor/filament` so they are immediately available to Vercel's CDN.

---

## 3. Database Migrations

Because Vercel runs in a serverless build container, you should **not** run database migrations during the Vercel build phase (as it does not have runtime environment variables and database access).

Instead, run the migrations from your local development machine pointing to the live TiDB database, or run them manually after deploying:

```bash
# To migrate your remote TiDB database from your local terminal:
php artisan migrate --force
```

---

## 4. Supabase Storage Integration (S3 Compatible)

To store uploaded media on production (since Vercel's disk is read-only), you need to configure Supabase Storage.

### 1. Install S3 Driver
Run the following command locally in your project root to install the required S3 Flysystem driver for Laravel:
```bash
composer require league/flysystem-aws-s3-v3
```

### 2. Configure S3 Credentials in Supabase
1. Open your **Supabase Dashboard**.
2. Go to **Storage** > **S3 Configuration** (in the sidebar).
3. Click **Generate New API Key** to get your **Access Key ID** and **Secret Access Key**.
4. Create a **Bucket** in your Supabase Storage dashboard (make sure the bucket is set to **Public** so that your portfolio images can be displayed publicly).
5. Copy these values into your Vercel environment variables as described in Section 1.


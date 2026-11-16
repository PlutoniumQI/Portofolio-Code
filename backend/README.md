# Backend — Laravel 13 + Filament 5

This app is both the local admin and the production API for the portfolio. Locally it uses SQLite and
the `public` disk; in production it runs on Render Free (Docker) with Neon Postgres and GitHub-hosted
uploads.

See the root [`README.md`](../README.md) for local setup, [`AGENTS.md`](AGENTS.md) for agent-facing
rules, and [`../docs/deploy-render-neon.md`](../docs/deploy-render-neon.md) for the deployment
runbook.

Quick start:

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
php artisan key:generate
New-Item -ItemType File -Force database\database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=8000
```

Admin: <http://127.0.0.1:8000/admin>

Production health checks:

```text
GET /up          Render health check
GET /api/health  lightweight JSON liveness endpoint
```

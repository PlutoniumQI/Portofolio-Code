# Backend — Filament admin + API

Laravel 13 + Filament 5. The backend rules, commands, API surface, content model, and production
notes live in [`AGENTS.md`](AGENTS.md). The deployment runbook is
[`../docs/deploy-vercel-neon.md`](../docs/deploy-vercel-neon.md).

Quick reference:

```powershell
$env:PATH = 'C:\Users\YOGA\.config\herd\bin\php85;' + $env:PATH
php artisan serve --host=127.0.0.1 --port=8000   # admin at /admin
php artisan test
vendor\bin\pint --test
php artisan portfolio:fallback                   # refresh the frontend snapshot
```

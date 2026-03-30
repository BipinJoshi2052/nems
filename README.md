# Nepal Education Management System (NEMS)

Multi-tenant SaaS foundation for education in Nepal (Montessori first, schools later). Central platform administration and database-per-tenant isolation via [Stancl Tenancy](https://tenancyforlaravel.com/).

**Stack:** Laravel 13 · PostgreSQL · Redis · Vue 3 · Vite · Bootstrap 5 · Docker

---

## Requirements

- PHP 8.3+ with extensions Laravel expects (pdo_pgsql, mbstring, openssl, etc.)
- Composer 2
- Node.js 20+ and npm
- Docker Desktop (optional, for the full compose stack)

---

## Quick install (local PHP + database)

1. **Clone and dependencies**

   ```bash
   git clone <repository-url> nems
   cd nems
   cp .env.example .env
   composer install
   npm install
   ```

2. **Application key**

   ```bash
   php artisan key:generate
   ```

3. **Database** — set `DB_*` in `.env` (see [Environment](#environment)). For PostgreSQL on the host, typical values are `DB_HOST=127.0.0.1`, `DB_PORT=5433`, `DB_DATABASE=nems`, `DB_USERNAME=nems`, `DB_PASSWORD=secret` when using Docker Postgres published on port **5433**.

4. **Migrate and seed**

   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Front-end**

   ```bash
   npm run dev
   ```

   In another terminal:

   ```bash
   php artisan serve
   ```

   Open `http://127.0.0.1:8000` (or your `APP_URL`).

---

## Docker Compose

Services: **app** (PHP-FPM), **nginx** (wildcard `*.school.test`), **postgres**, **redis**, **scheduler**, **vite**, **mailpit**, **minio**.

1. Copy `.env.example` → `.env`, set `APP_KEY` (`php artisan key:generate`), and align database variables with Compose (see below).

2. **Hosts file** (administrator edit):

   ```
   127.0.0.1 school.test
   127.0.0.1 greenpark.school.test
   ```

3. Start:

   ```bash
   docker compose up -d --build
   ```

4. Inside the app container (or from the host if PHP/Composer are local and DB is reachable):

   ```bash
   docker compose exec app php artisan migrate --force
   docker compose exec app php artisan db:seed --force
   ```

5. **App URL:** `http://school.test` (nginx on port **80**).

### Published ports

| Port | Service        | Notes                                      |
|------|----------------|--------------------------------------------|
| 80   | Nginx          | HTTP app                                   |
| 5173 | Vite (dev)     | Hot reload when `vite` service runs        |
| 5433 | PostgreSQL     | Host port → container `5432` (see `.env`)  |
| 6379 | Redis          | Optional host access                       |
| 8025 | Mailpit        | Web UI for mail                            |
| 1025 | Mailpit        | SMTP (app uses `mailpit:1025` in Docker)   |
| 9000 | MinIO          | S3 API (not wired in Laravel yet)          |
| 9001 | MinIO          | Console                                    |

Compose overrides `DB_HOST=postgres` and `DB_PORT=5432` for **app** and **scheduler**. On your **host**, tools such as DBeaver should use `127.0.0.1` and **`DB_PORT` / `POSTGRES_HOST_PORT` (default 5433)** so you connect to the container, not another local PostgreSQL on 5432.

---

## Default seeded credentials

After `php artisan db:seed`, a **platform administrator** exists for the central (landlord) database:

| Field    | Value              |
|----------|--------------------|
| Email    | `admin@school.test` |
| Password | `ChangeMe!123`     |

Change this password immediately after first login (`/platform` → password change).

### What seeders run

- **PlatformAdminSeeder** — default platform admin (above).
- **TranslationSeeder** — sample English/Nepali rows in the `translations` table (validation/common/mail keys).

---

## Environment

Copy `.env.example` to `.env` and review:

- **`APP_URL`** — e.g. `http://school.test` with Docker/nginx, or `http://127.0.0.1:8000` with `php artisan serve`.
- **`DB_*`** — PostgreSQL. Host PHP on Windows/Mac: `DB_HOST=127.0.0.1`, `DB_PORT=5433` (or your `POSTGRES_HOST_PORT`). Inside Docker, Compose sets `postgres:5432` for app/scheduler.
- **`POSTGRES_HOST_PORT`** — host port mapped to Postgres (default **5433**).
- **`QUEUE_CONNECTION`** — `database` (run a worker: `php artisan queue:work` or `queue:listen`).
- **`CACHE_STORE`** — `file` (per project defaults).
- **`MAIL_*`** — Mailpit in Docker: `MAIL_HOST=mailpit`, `MAIL_PORT=1025`. Web UI: `http://127.0.0.1:8025`.

Generate `APP_KEY` if missing:

```bash
php artisan key:generate
```

---

## Useful commands

```bash
php artisan test              # PHPUnit
composer deptrac              # Architecture rules (deptrac.yaml)
php artisan translations:export  # Export `translations` table to lang + Vue JSON (when configured)
```

Composer also defines `composer run setup` for a scripted first-time install (see `composer.json`).

---

## Project layout (high level)

| Path | Purpose |
|------|---------|
| `app/Core/` | Shared services, contracts, helpers |
| `app/Platform/` | Platform (landlord) admin UI |
| `app/Verticals/Montessori/` | Montessori vertical (future) |
| `app/Verticals/School/` | School vertical (placeholder) |
| `database/migrations/` | Central DB migrations |
| `database/migrations/tenant/` | Tenant DB migrations |

---

## Security note

Default passwords and Mailpit/MinIO defaults are for **local development only**. Do not use them in production.

---

## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Libra-Sys-PHP

## Requirements

- PHP 8.3+
- MySQL 8.x

## Local setup

1. Copy `.env.example` to `.env` and update database credentials.
   - Optional: create environment overlays such as `.env.local` or `.env.test` and set `APP_ENV` to load them after `.env`.
2. Create the configured MySQL database (default: `librasys`).
3. Run migrations:

   ```bash
   php bin/migrate.php
   ```

4. Run deterministic seed data:

   ```bash
   php bin/seed.php
   ```

5. Start the PHP server:

   ```bash
   php -S localhost:8000 -t public
   ```

6. Open:
   - Home: `http://localhost:8000/`
   - Database verification page: `http://localhost:8000/db-check`

## Environment variables

- `APP_NAME`, `APP_ENV`, `APP_DEBUG`, `APP_URL`
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
- `DB_TEST_NAME` for the separate test database

`.env`, `.env.local`, and `.env.test` are ignored to avoid committing secrets.

## Test database setup

Create a second schema (for example `librasys_test`) and set it in `DB_TEST_NAME`. To initialize the test schema with the same migration and deterministic seed workflow, temporarily run commands with `DB_NAME` overridden:

```bash
DB_NAME=librasys_test php bin/migrate.php
DB_NAME=librasys_test php bin/seed.php
```

This keeps browser and integration tests isolated from local development data.

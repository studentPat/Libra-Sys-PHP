# LibraSys PHP application

LibraSys is a PHP 8.3+ server-rendered library management system backed by MySQL 8.x.
This repository currently contains the application and database foundation used by later
catalog, member, borrowing, and reporting workflows.

## Requirements

- PHP 8.3+ with the `pdo_mysql` extension
- Composer 2.x
- MySQL 8.x

## Local setup

1. Create a database and application user using the test setup in
   [`docs/database-setup.md`](docs/database-setup.md).
2. Install dependencies:

   ```text
   composer install
   ```

3. Copy `.env.example` to `.env` and set the local database values.
4. Apply the schema and deterministic seed data:

   ```text
   php bin/migrate.php
   php bin/seed.php
   ```

5. Start the development server:

   ```text
   php -S 127.0.0.1:8080 public/index.php
   ```

6. Open `http://127.0.0.1:8080/health/database`. A successful response confirms
   that PHP can connect to MySQL and execute a prepared statement.

## Tests

Run the test suite with:

```text
composer test
```

The database integration test runs when `TEST_DB_*` variables are provided and is
skipped otherwise. The HTTP health path can be verified with the built-in server:

```text
curl http://127.0.0.1:8080/health/database
```

Application errors are logged server-side and rendered as a generic error page.
Do not commit `.env` or any database credentials.
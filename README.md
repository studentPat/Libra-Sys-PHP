# Libra-Sys-PHP

## Requirements

- PHP 8.3+
- MySQL 8+
- PHP extensions: `pdo`, `pdo_mysql`

## Local setup

1. Copy the environment template:
   ```bash
   cp .env.example .env
   ```
2. Update `.env` with local MySQL credentials.
3. Run migrations and seed data:
   ```bash
   php /home/runner/work/Libra-Sys-PHP/Libra-Sys-PHP/bin/migrate.php
   php /home/runner/work/Libra-Sys-PHP/Libra-Sys-PHP/bin/seed.php
   ```
4. Start the app:
   ```bash
   php -S 127.0.0.1:8000 -t /home/runner/work/Libra-Sys-PHP/Libra-Sys-PHP/public
   ```

## Verification path

Open `http://127.0.0.1:8000/verify-db` after setup. A successful page confirms that the application connected to MySQL using PDO.

## Test database setup

For browser/integration tests, create a separate MySQL database (for example `librasys_test`) and set `DB_TEST_NAME` in `.env`.

Example setup:

```sql
CREATE DATABASE librasys_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON librasys_test.* TO 'libra_web'@'%';
FLUSH PRIVILEGES;
```

Use the same migration and seed commands after setting `DB_NAME` to the test database value for the test run.

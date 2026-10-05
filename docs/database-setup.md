# Local database setup

The application uses a restricted MySQL account for normal web requests. Schema
migrations should be run with an account that can create and alter tables; the
web account only needs the privileges required by the application workflows.

For a local development database, run the following as a MySQL administrator:

```sql
CREATE DATABASE librasys CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
CREATE DATABASE librasys_test CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE USER 'libra_web'@'localhost' IDENTIFIED BY 'replace-with-a-local-password';
GRANT SELECT, INSERT, UPDATE, DELETE ON librasys.* TO 'libra_web'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON librasys_test.* TO 'libra_web'@'localhost';
FLUSH PRIVILEGES;
```

For the first migration, use a migration-capable account:

```text
DB_USER=libra_migration
DB_PASSWORD=...
php bin/migrate.php
php bin/seed.php
```

After migration and seeding, set `.env` to the restricted `libra_web` account.
The test suite uses the `TEST_DB_*` values when they are all present. Use a
separate test database because the integration test may create and remove
foundation tables.

Never commit `.env`, passwords, encryption keys, or production connection
details.

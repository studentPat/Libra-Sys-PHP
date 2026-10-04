#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';
require_once dirname(__DIR__) . '/src/Database.php';

use LibraSys\Database;

$config = appConfig();
$pdo = Database::connect($config['db']);

$pdo->exec(
    'CREATE TABLE IF NOT EXISTS schema_migrations (
        migration VARCHAR(255) PRIMARY KEY,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
);

$appliedRows = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll();
$applied = array_flip(array_column($appliedRows, 'migration'));

$migrationFiles = glob(dirname(__DIR__) . '/db/migrations/*.sql');
sort($migrationFiles);

if ($migrationFiles === []) {
    fwrite(STDOUT, "No migrations found.\n");
    exit(0);
}

$recordStatement = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (:migration)');

foreach ($migrationFiles as $migrationFile) {
    $migrationName = basename($migrationFile);

    if (isset($applied[$migrationName])) {
        fwrite(STDOUT, "Skipping {$migrationName}\n");
        continue;
    }

    $sql = file_get_contents($migrationFile);
    if ($sql === false) {
        throw new RuntimeException('Unable to read migration file: ' . $migrationName);
    }

    $pdo->beginTransaction();

    try {
        $pdo->exec($sql);
        $recordStatement->execute(['migration' => $migrationName]);
        $pdo->commit();
        fwrite(STDOUT, "Applied {$migrationName}\n");
    } catch (Throwable $throwable) {
        $pdo->rollBack();
        throw $throwable;
    }
}

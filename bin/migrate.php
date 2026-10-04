<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/src/bootstrap.php';
$pdo = DatabaseConnection::make($config['db']);

$pdo->exec('CREATE TABLE IF NOT EXISTS schema_migrations (migration VARCHAR(255) PRIMARY KEY, migrated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)');

$applied = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);
$appliedSet = array_fill_keys($applied ?: [], true);

$files = glob(dirname(__DIR__) . '/database/migrations/*.sql') ?: [];
sort($files);

foreach ($files as $file) {
    $name = basename($file);
    if (isset($appliedSet[$name])) {
        echo "Skipping {$name} (already applied)" . PHP_EOL;
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        throw new RuntimeException("Unable to read migration file: {$file}");
    }

    $pdo->beginTransaction();

    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (:migration)');
        $stmt->execute(['migration' => $name]);
        $pdo->commit();
        echo "Applied {$name}" . PHP_EOL;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}

echo 'Migrations complete.' . PHP_EOL;

<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/src/bootstrap.php';
$pdo = DatabaseConnection::make($config['db']);

$seedFile = dirname(__DIR__) . '/database/seeds/001_core_seed.sql';
$sql = file_get_contents($seedFile);

if ($sql === false) {
    throw new RuntimeException('Unable to read seed file.');
}

$pdo->beginTransaction();

try {
    $pdo->exec($sql);
    $pdo->commit();
    echo "Seed complete: {$seedFile}" . PHP_EOL;
} catch (Throwable $exception) {
    $pdo->rollBack();
    throw $exception;
}

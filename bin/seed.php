#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';
require_once dirname(__DIR__) . '/src/Database.php';

use LibraSys\Database;

$config = appConfig();
$pdo = Database::connect($config['db']);

$seedFiles = glob(dirname(__DIR__) . '/db/seeds/*.sql');
sort($seedFiles);

if ($seedFiles === []) {
    fwrite(STDOUT, "No seed files found.\n");
    exit(0);
}

foreach ($seedFiles as $seedFile) {
    $seedName = basename($seedFile);
    $sql = file_get_contents($seedFile);

    if ($sql === false) {
        throw new RuntimeException('Unable to read seed file: ' . $seedName);
    }

    $pdo->beginTransaction();

    try {
        $pdo->exec($sql);
        $pdo->commit();
        fwrite(STDOUT, "Seeded {$seedName}\n");
    } catch (Throwable $throwable) {
        $pdo->rollBack();
        throw $throwable;
    }
}

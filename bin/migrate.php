<?php

declare(strict_types=1);

use LibraSys\Config\Env;
use LibraSys\Config\DatabaseConfig;
use LibraSys\Database\Database;
use LibraSys\Database\Migrator;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
$env = Env::load($root . DIRECTORY_SEPARATOR . '.env');
$pdo = Database::connect(DatabaseConfig::fromEnvironment($env));

$count = (new Migrator($pdo, $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations'))->migrate();
printf("Applied %d migration(s).%s", $count, PHP_EOL);

<?php

declare(strict_types=1);

use LibraSys\Config\DatabaseConfig;
use LibraSys\Database\Database;
use LibraSys\Database\Seeder;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
$pdo = Database::connect(DatabaseConfig::fromEnvFile($root . DIRECTORY_SEPARATOR . '.env'));

$count = (new Seeder($pdo, $root . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'seeds'))->seed();
printf("Applied %d seed file(s).%s", $count, PHP_EOL);

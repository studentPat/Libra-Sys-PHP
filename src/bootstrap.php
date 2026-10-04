<?php

declare(strict_types=1);

require_once __DIR__ . '/Support/Env.php';
require_once __DIR__ . '/Database/Connection.php';
require_once __DIR__ . '/View/layout.php';

Env::load(dirname(__DIR__) . '/.env');
$environment = Env::get('APP_ENV', 'local');

if ($environment !== null && $environment !== '') {
    Env::load(dirname(__DIR__) . '/.env.' . $environment);
}

return [
    'app' => require dirname(__DIR__) . '/config/app.php',
    'db' => require dirname(__DIR__) . '/config/database.php',
];

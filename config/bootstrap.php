<?php

declare(strict_types=1);

require_once __DIR__ . '/env.php';

loadEnvFile(dirname(__DIR__) . '/.env');

function appConfig(): array
{
    static $config;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'app_name' => $_ENV['APP_NAME'] ?? 'LibraSys',
        'app_env' => $_ENV['APP_ENV'] ?? 'development',
        'app_debug' => filter_var($_ENV['APP_DEBUG'] ?? '0', FILTER_VALIDATE_BOOL),
        'db' => [
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
            'name' => $_ENV['DB_NAME'] ?? 'librasys',
            'username' => $_ENV['DB_USER'] ?? '',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
            'ssl_ca' => $_ENV['DB_SSL_CA'] ?? '',
        ],
    ];

    return $config;
}

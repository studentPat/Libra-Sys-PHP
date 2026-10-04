<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/bootstrap.php';
require_once dirname(__DIR__) . '/src/Database.php';
require_once dirname(__DIR__) . '/src/View.php';

use LibraSys\Database;
use LibraSys\View;

$config = appConfig();

set_exception_handler(static function (\Throwable $exception): void {
    error_log($exception->getMessage());
    View::renderError('Unable to complete the request right now.');
});

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

if ($path === '/') {
    View::render('home', [
        'title' => $config['app_name'] . ' Foundation',
    ]);
    return;
}

if ($path === '/verify-db') {
    $pdo = Database::connect($config['db']);

    $statement = $pdo->prepare('SELECT DATABASE() AS database_name, NOW() AS server_time');
    $statement->execute();
    $result = $statement->fetch();

    View::render('verify-db', [
        'title' => 'Database verification',
        'databaseName' => $result['database_name'] ?? '(unknown)',
        'serverTime' => $result['server_time'] ?? '(unknown)',
    ]);

    return;
}

View::renderError('Page not found.', 404);

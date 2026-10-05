<?php

declare(strict_types=1);

use LibraSys\Config\Env;
use LibraSys\Config\DatabaseConfig;
use LibraSys\Database\Database;
use LibraSys\Http\Router;
use LibraSys\View\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
try {
    $env = Env::load($root . DIRECTORY_SEPARATOR . '.env');
    $renderer = new Renderer($root . DIRECTORY_SEPARATOR . 'templates');
    $router = new Router();

    $router->get('/', static fn (): string => $renderer->render('home', [
        'title' => 'LibraSys',
    ]));

    $router->get('/health/database', static function () use ($env, $renderer): string {
        try {
            $pdo = Database::connect(DatabaseConfig::fromEnvironment($env));
            Database::ping($pdo);

            return $renderer->render('health/database', [
                'title' => 'Database health',
                'connected' => true,
            ]);
        } catch (Throwable $error) {
            error_log($error->getMessage());
            http_response_code(503);

            return $renderer->render('health/database', [
                'title' => 'Database health',
                'connected' => false,
            ]);
        }
    });

    echo $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
} catch (Throwable $error) {
    error_log($error->getMessage());
    http_response_code(500);
    $renderer = new Renderer($root . DIRECTORY_SEPARATOR . 'templates');
    echo $renderer->render('error', [
        'title' => 'Application error',
    ]);
}

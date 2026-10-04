<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/src/bootstrap.php';
$appConfig = $config['app'];
$dbConfig = $config['db'];
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

try {
    if ($path === '/' || $path === '') {
        echo renderLayout(
            $appConfig,
            'Home',
            '<h2>Application foundation is running.</h2>'
            . '<p>Run <code>php bin/migrate.php</code> and <code>php bin/seed.php</code> to initialize the database.</p>'
        );
        exit;
    }

    if ($path === '/db-check') {
        $pdo = DatabaseConnection::make($dbConfig);
        $stmt = $pdo->query('SELECT DATABASE() AS database_name, CURRENT_USER() AS current_user, NOW() AS server_time');
        $status = $stmt->fetch() ?: [];

        $content = '<h2>Database connection verified.</h2>'
            . '<p class="ok">The application successfully connected to MySQL using PDO.</p>'
            . '<ul>'
            . '<li><strong>Database:</strong> ' . htmlspecialchars((string) ($status['database_name'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') . '</li>'
            . '<li><strong>User:</strong> ' . htmlspecialchars((string) ($status['current_user'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') . '</li>'
            . '<li><strong>Server time:</strong> ' . htmlspecialchars((string) ($status['server_time'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') . '</li>'
            . '</ul>';

        echo renderLayout($appConfig, 'DB Check', $content);
        exit;
    }

    echo renderErrorPage($appConfig, 404, 'The requested page was not found.');
} catch (Throwable $exception) {
    $safeMessage = $appConfig['debug']
        ? $exception->getMessage()
        : 'Please try again later or contact an administrator.';

    echo renderErrorPage($appConfig, 500, $safeMessage);
}

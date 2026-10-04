<?php

declare(strict_types=1);

function renderLayout(array $appConfig, string $title, string $content): string
{
    $appName = htmlspecialchars($appConfig['name'], ENT_QUOTES, 'UTF-8');
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

    return <<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$title} · {$appName}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; color: #1f2937; }
        main { max-width: 760px; margin: 0 auto; }
        .card { border: 1px solid #d1d5db; border-radius: 8px; padding: 1rem 1.25rem; margin-top: 1rem; }
        code { background: #f3f4f6; padding: .15rem .35rem; border-radius: 4px; }
        nav a { margin-right: 1rem; }
        .ok { color: #0f766e; }
        .error { color: #b91c1c; }
    </style>
</head>
<body>
<main>
    <h1>{$appName}</h1>
    <nav>
        <a href="/">Home</a>
        <a href="/db-check">DB Check</a>
    </nav>
    <div class="card">{$content}</div>
</main>
</body>
</html>
HTML;
}

function renderErrorPage(array $appConfig, int $statusCode, string $safeMessage): string
{
    http_response_code($statusCode);

    $content = sprintf(
        '<h2>Something went wrong</h2><p class="error">%s</p>',
        htmlspecialchars($safeMessage, ENT_QUOTES, 'UTF-8')
    );

    return renderLayout($appConfig, 'Error', $content);
}

<?php

declare(strict_types=1);

namespace LibraSys;

final class View
{
    public static function render(string $template, array $data = []): void
    {
        $templatesPath = dirname(__DIR__) . '/templates';
        $templatePath = $templatesPath . '/' . $template . '.php';

        if (!is_file($templatePath)) {
            throw new \RuntimeException('Template not found: ' . $template);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $templatePath;
        $content = (string) ob_get_clean();

        $title = $data['title'] ?? 'LibraSys';

        require $templatesPath . '/layout.php';
    }

    public static function renderError(string $message = 'An unexpected error occurred.', int $statusCode = 500): void
    {
        http_response_code($statusCode);
        self::render('error', [
            'title' => 'Error',
            'message' => $message,
        ]);
    }
}

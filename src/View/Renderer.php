<?php

declare(strict_types=1);

namespace LibraSys\View;

use RuntimeException;

final class Renderer
{
    public function __construct(private readonly string $directory)
    {
    }

    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): string
    {
        $path = $this->directory . DIRECTORY_SEPARATOR . $template . '.php';
        if (!is_file($path)) {
            throw new RuntimeException(sprintf('Template not found: %s', $template));
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $path;
        $content = ob_get_clean();
        if ($content === false) {
            throw new RuntimeException('Template rendering failed.');
        }

        return $this->renderLayout($content, $data['title'] ?? 'LibraSys');
    }

    private function renderLayout(string $content, string $title): string
    {
        $layout = $this->directory . DIRECTORY_SEPARATOR . 'layout.php';
        ob_start();
        require $layout;
        $html = ob_get_clean();
        if ($html === false) {
            throw new RuntimeException('Layout rendering failed.');
        }

        return $html;
    }
}

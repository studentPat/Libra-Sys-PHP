<?php

declare(strict_types=1);

namespace LibraSys\Http;

use Closure;

final class Router
{
    /** @var array<string, Closure(): string> */
    private array $getRoutes = [];

    /** @param Closure(): string $handler */
    public function get(string $path, Closure $handler): void
    {
        $this->getRoutes[$path] = $handler;
    }

    public function dispatch(string $method, string $path): string
    {
        if ($method !== 'GET' || !isset($this->getRoutes[$path])) {
            http_response_code(404);
            return '<h1>Not found</h1>';
        }

        return ($this->getRoutes[$path])();
    }
}

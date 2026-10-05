<?php

declare(strict_types=1);

namespace LibraSys\Config;

final class DatabaseConfig
{
    /** @return array<string, string> */
    public static function fromEnvFile(string $path, string $prefix = 'DB_'): array
    {
        return self::fromEnvironment(Env::load($path), $prefix);
    }

    /**
     * @return array<string, string>
     */
    public static function fromEnvironment(array $environment, string $prefix = 'DB_'): array
    {
        return [
            'host' => Env::required($environment, $prefix . 'HOST'),
            'port' => Env::required($environment, $prefix . 'PORT'),
            'name' => Env::required($environment, $prefix . 'NAME'),
            'user' => Env::required($environment, $prefix . 'USER'),
            'password' => $environment[$prefix . 'PASSWORD'] ?? '',
            'charset' => $environment[$prefix . 'CHARSET'] ?? 'utf8mb4',
        ];
    }
}

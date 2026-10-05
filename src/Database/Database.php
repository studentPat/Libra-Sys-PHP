<?php

declare(strict_types=1);

namespace LibraSys\Database;

use PDO;

final class Database
{
    /**
     * @param array<string, string> $config
     */
    public static function connect(array $config): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            (int) $config['port'],
            $config['name'],
            $config['charset'],
        );

        return new PDO($dsn, $config['user'], $config['password'], self::options());
    }

    /**
     * @return array<int, mixed>
     */
    public static function options(): array
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
    }

    public static function ping(PDO $pdo): int
    {
        $statement = $pdo->prepare('SELECT 1');
        $statement->execute();
        return (int) $statement->fetchColumn();
    }
}

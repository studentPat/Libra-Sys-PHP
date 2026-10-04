<?php

declare(strict_types=1);

namespace LibraSys;

use PDO;

final class Database
{
    public static function connect(array $dbConfig): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['name'],
            $dbConfig['charset']
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if (($dbConfig['ssl_ca'] ?? '') !== '') {
            $sslOption = constant('PDO::MYSQL_ATTR_SSL_CA');
            if (is_int($sslOption)) {
                $options[$sslOption] = $dbConfig['ssl_ca'];
            }
        }

        return new PDO(
            $dsn,
            (string) $dbConfig['username'],
            (string) $dbConfig['password'],
            $options
        );
    }
}

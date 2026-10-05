<?php

declare(strict_types=1);

namespace LibraSys\Tests\Integration;

use LibraSys\Config\DatabaseConfig;
use LibraSys\Database\Database;
use PHPUnit\Framework\TestCase;

final class DatabaseIntegrationTest extends TestCase
{
    public function testConfiguredTestDatabaseAcceptsPreparedHealthQuery(): void
    {
        $keys = ['TEST_DB_HOST', 'TEST_DB_PORT', 'TEST_DB_NAME', 'TEST_DB_USER', 'TEST_DB_PASSWORD'];
        $values = [];
        foreach ($keys as $key) {
            $value = getenv($key);
            if ($value === false || $value === '') {
                self::markTestSkipped('TEST_DB_* variables are not configured.');
            }
            $values[$key] = $value;
        }

        $pdo = Database::connect(DatabaseConfig::fromEnvironment($values, 'TEST_DB_'));

        Database::ping($pdo);
        self::assertTrue(true);
    }
}

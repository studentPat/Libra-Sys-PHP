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
        foreach ($keys as $key) {
            $value = getenv($key);
            if ($value === false || $value === '') {
                self::markTestSkipped('TEST_DB_* variables are not configured.');
            }
        }

        $pdo = Database::connect(DatabaseConfig::fromEnvironment([
            'TEST_HOST' => (string) getenv('TEST_DB_HOST'),
            'TEST_PORT' => (string) getenv('TEST_DB_PORT'),
            'TEST_NAME' => (string) getenv('TEST_DB_NAME'),
            'TEST_USER' => (string) getenv('TEST_DB_USER'),
            'TEST_PASSWORD' => (string) getenv('TEST_DB_PASSWORD'),
        ], 'TEST_'));

        self::assertSame(1, Database::ping($pdo));
    }
}

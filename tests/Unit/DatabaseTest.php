<?php

declare(strict_types=1);

namespace LibraSys\Tests\Unit;

use LibraSys\Database\Database;
use PDO;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{
    public function testDatabaseUsesExceptionModeAndNativePreparedStatements(): void
    {
        $options = Database::options();

        self::assertSame(PDO::ERRMODE_EXCEPTION, $options[PDO::ATTR_ERRMODE]);
        self::assertSame(PDO::FETCH_ASSOC, $options[PDO::ATTR_DEFAULT_FETCH_MODE]);
        self::assertFalse($options[PDO::ATTR_EMULATE_PREPARES]);
    }
}

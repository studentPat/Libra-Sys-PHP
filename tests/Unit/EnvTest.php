<?php

declare(strict_types=1);

namespace LibraSys\Tests\Unit;

use LibraSys\Config\Env;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class EnvTest extends TestCase
{
    public function testItLoadsCommentsQuotedValuesAndEmptyValues(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'librasys-env-');
        self::assertNotFalse($path);
        file_put_contents($path, "# comment\nAPP_ENV=testing\nAPP_NAME=\"Libra Sys\"\nEMPTY=\n");

        try {
            self::assertSame([
                'APP_ENV' => 'testing',
                'APP_NAME' => 'Libra Sys',
                'EMPTY' => '',
            ], Env::load($path));
        } finally {
            unlink($path);
        }
    }

    public function testMissingRequiredValueIsRejected(): void
    {
        $this->expectException(RuntimeException::class);
        Env::required([], 'DB_HOST');
    }
}

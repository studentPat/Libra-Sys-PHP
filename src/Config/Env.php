<?php

declare(strict_types=1);

namespace LibraSys\Config;

use RuntimeException;

final class Env
{
    /**
     * @return array<string, string>
     */
    public static function load(string $path): array
    {
        if (!is_file($path)) {
            throw new RuntimeException(sprintf('Environment file not found: %s', $path));
        }

        $values = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            throw new RuntimeException(sprintf('Environment file could not be read: %s', $path));
        }

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!preg_match('/^([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$/', $line, $matches)) {
                throw new RuntimeException(sprintf('Invalid environment entry on line %d.', $lineNumber + 1));
            }

            $value = trim($matches[2]);
            if (
                strlen($value) >= 2
                && (($value[0] === '"' && $value[strlen($value) - 1] === '"')
                    || ($value[0] === "'" && $value[strlen($value) - 1] === "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $values[$matches[1]] = $value;
        }

        return $values;
    }

    /**
     * @param array<string, string> $values
     */
    public static function required(array $values, string $key): string
    {
        $value = $values[$key] ?? '';
        if ($value === '') {
            throw new RuntimeException(sprintf('Required environment value "%s" is missing.', $key));
        }

        return $value;
    }
}

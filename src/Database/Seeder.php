<?php

declare(strict_types=1);

namespace LibraSys\Database;

use PDO;
use RuntimeException;

final class Seeder
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $directory,
    ) {
    }

    public function seed(): int
    {
        $files = glob($this->directory . DIRECTORY_SEPARATOR . '*.sql');
        if ($files === false) {
            throw new RuntimeException('Seed directory could not be read.');
        }
        sort($files);

        $count = 0;
        foreach ($files as $file) {
            $sql = file_get_contents($file);
            if ($sql === false) {
                throw new RuntimeException(sprintf('Seed file could not be read: %s', basename($file)));
            }

            $this->pdo->beginTransaction();
            try {
                $this->pdo->exec($sql);
                $this->pdo->commit();
                $count++;
            } catch (\Throwable $error) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                throw $error;
            }
        }

        return $count;
    }
}

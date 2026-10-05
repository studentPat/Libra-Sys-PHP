<?php

declare(strict_types=1);

namespace LibraSys\Database;

use PDO;
use RuntimeException;

final class Migrator
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $directory,
    ) {
    }

    public function migrate(): int
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS schema_migrations (
                migration_name VARCHAR(255) PRIMARY KEY,
                applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB',
        );

        $applied = $this->appliedMigrations();
        $files = glob($this->directory . DIRECTORY_SEPARATOR . '*.sql');
        if ($files === false) {
            throw new RuntimeException('Migration directory could not be read.');
        }
        sort($files);

        $count = 0;
        foreach ($files as $file) {
            $name = basename($file);
            if (isset($applied[$name])) {
                continue;
            }

            $sql = file_get_contents($file);
            if ($sql === false) {
                throw new RuntimeException(sprintf('Migration could not be read: %s', $name));
            }

            $this->pdo->beginTransaction();
            try {
                $this->pdo->exec($sql);
                $statement = $this->pdo->prepare(
                    'INSERT INTO schema_migrations (migration_name) VALUES (:migration_name)',
                );
                $statement->execute(['migration_name' => $name]);
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

    /**
     * @return array<string, true>
     */
    private function appliedMigrations(): array
    {
        $rows = $this->pdo->query('SELECT migration_name FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);
        return array_fill_keys($rows, true);
    }
}

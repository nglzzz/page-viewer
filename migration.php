<?php

use Core\ConnectionInterface;

require_once __DIR__ . '/core/Application.php';

$application = Core\Application::getInstance();

class Migration
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
        $this->initTable();
    }

    public function initTable(): void
    {
        $this->connection->execute('CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );');
    }

    public function runAll(): void
    {
        $finished = $this->getFinishedMigrations();
        $migrations = $this->getMigrationFiles();

        foreach ($migrations as $migration) {
            $migrationName = basename($migration);
            if (isset($finished[$migrationName])) {
                continue;
            }

            $sql = file_get_contents($migration);

            $this->connection->beginTransaction();
            try {
                $this->connection->execute($sql);
                $this->connection->execute("INSERT INTO migrations (migration) VALUES ('$migrationName')");
                $this->connection->commit();

                echo "Migration $migration has been successfully executed." . PHP_EOL;
            } catch (\Exception $e) {
                $this->connection->rollback();
                echo "Migration $migration failed: " . $e->getMessage() . PHP_EOL;
                break;
            }
        }
    }

    public function getFinishedMigrations(): array
    {
        $migrations = $this->connection->get('SELECT * FROM migrations ORDER BY created_at ASC');

        $result = [];
        foreach ($migrations as $migration) {
            $result[$migration['migration']] = $migration['created_at'];
        }
        return $result;
    }

    public function getMigrationFiles(): array
    {
        $path = __DIR__ . '/migrations/';

        $list = glob($path . '*.sql');
        natsort($list);

        return $list;
    }
}

if ($argc !== 2 || $argv[1] !== 'run') {
    echo "Usage: php migrate.php <run>\n";
    exit(1);
}

$migration = new Migration($application->getConnection());
$migration->runAll();

<?php

declare(strict_types=1);

namespace Core;

class MysqlConnection implements ConnectionInterface
{
    private \PDO $connection;

    public function __construct(string $host, string $database, string $user, string $password, int $port = 3306)
    {
        $this->init($host, $database, $user, $password, $port);
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }

    public function execute(string $sql, array $params = [])
    {
        return $this->connection->prepare($sql)->execute($params);
    }

    public function get(string $sql, array $params = []): array
    {
        $sth = $this->connection->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll() ?? [];
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }

    private function init(string $host, string $database, string $username, string $password, int $port): void
    {
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->connection = new \PDO($dsn, $username, $password, $options);
    }
}

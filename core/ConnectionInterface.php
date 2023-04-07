<?php

namespace Core;

interface ConnectionInterface
{
    public function getConnection();
    public function execute(string $sql, array $params = []);
    public function get(string $sql, array $params = []): array;
    public function beginTransaction(): bool;
    public function commit(): bool;
    public function rollback(): bool;
}

<?php

declare(strict_types=1);

namespace Core;

final class ConnectionFactory
{
    public static function create(string $connectionType): string
    {
        switch (strtolower($connectionType)) {
            case 'mysql':
                return MysqlConnection::class;
            // todo: add new connections if needed
            default: throw new \RuntimeException('Invalid connection type');
        }
    }
}

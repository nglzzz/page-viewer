<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\PageDTO;
use Core\ConnectionInterface;

class PageService
{
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function findPage(string $name): ?PageDTO
    {
        $page = $this->findPageFromDatabase($name);

        if ($page) {
            return $page;
        }

        return $this->findPageFromFiles($name);
    }

    private function findPageFromDatabase(string $name): ?PageDTO
    {
        $page = $this->connection->get('SELECT * FROM page WHERE title = :title', [
            'title' => $name,
        ])[0] ?? null;

        if ($page) {
            $page = new PageDTO($page['title'], $page['text'], $page['mime']);
        }

        return $page;
    }

    private function findPageFromFiles(string $name): ?PageDTO
    {
        $path = PROJECT_PATH . 'storage/pages';
        $files = glob($path . '/*');

        foreach ($files as $file) {
            $fileInfo = pathinfo($file);

            if ($name === $fileInfo['filename']) {
                $content = file_get_contents($file);
                $extension = $fileInfo['extension'] === 'txt' ? 'text/plain' : 'text/html';
                return new PageDTO($fileInfo['filename'], $content, $extension);
            }
        }
        return null;
    }
}

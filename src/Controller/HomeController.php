<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\PageDTO;
use App\Utils\TextUtils;
use Core\Controller;

class HomeController extends Controller
{
    public function __invoke(): string
    {
        $name = $this->getRequest()->getInput('name');
        $page = null;

        if ($name) {
            $page = $this->findPage($name);
            $text = '<p>Page does not exist</p>';
        }

        if ($page) {
            if ($page->mime === 'text/plain') {
                $textUtils = new TextUtils();
                $text = $textUtils->convertTextToHtml($page->text);
            } else {
                $text = $page->text;
            }
        }


        return $this->getView()->view('home', [
            'name' => $name,
            'page' => $page,
            'text' => $text ?? '',
        ]);
    }

    private function findPage(string $name): ?PageDTO
    {
        $page = $this->findPageFromDatabase($name);

        if ($page) {
            return $page;
        }

        return $this->findPageFromFiles($name);
    }

    private function findPageFromDatabase(string $name): ?PageDTO
    {
        $connection = $this->getApplication()->getConnection();
        $page = $connection->get('SELECT * FROM page WHERE title = :title', [
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

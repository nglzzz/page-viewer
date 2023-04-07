<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PageService;
use App\Utils\TextUtils;
use Core\Controller;

class HomeController extends Controller
{
    public function __invoke(): string
    {
        $name = $this->getRequest()->getInput('name');
        $page = null;

        if ($name) {
            $pageService = new PageService($this->getApplication()->getConnection()); // todo: need to realize DI
            $page = $pageService->findPage($name);
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
}

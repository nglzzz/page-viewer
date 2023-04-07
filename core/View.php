<?php

declare(strict_types=1);

namespace Core;

final class View
{
    private const VIEWS_PATH = __DIR__ . '/../views/';
    private ?string $layout = null;

    public function __construct(?string $layout = 'layout.php')
    {
        $this->setLayout($layout);
    }

    public function setLayout(?string $layout): void
    {
        $this->layout = $layout;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function getLayoutPath(): ?string
    {
        if ($this->layout) {
            return self::VIEWS_PATH . $this->layout;
        }
        return null;
    }

    public function view(string $view, array $data = []): string
    {
        ob_start();
        extract($data, \EXTR_OVERWRITE);

        if ($this->layout && file_exists($this->getLayoutPath())) {
            require_once $this->getLayoutPath();
        }

        $viewPath = self::VIEWS_PATH . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View $view not found");
        }

        require $viewPath;
        return ob_get_clean();
    }

    public function render(string $content): void
    {
        echo $content;
    }
}

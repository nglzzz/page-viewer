<?php

declare(strict_types=1);

namespace Core;

final class Autoload
{
    private array $namespaces;

    public function __construct(array $namespaces = [])
    {
        $this->namespaces = $namespaces;
    }

    public function addNamespace(string $name, string $path): void
    {
        $this->namespaces[$name] = $path;
    }

    public function register(): void
    {
        spl_autoload_register(function (string $class) {
            $pathParts = explode('\\', $class);
            $namespace = array_shift($pathParts);

            if (!isset($this->namespaces[$namespace])) {
                return false;
            }

            $file = $this->namespaces[$namespace] . '/' . str_replace('\\', '/', implode('\\', $pathParts)) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return true;
            }
            return false;
        });
    }
}

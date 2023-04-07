<?php

declare(strict_types=1);

namespace Core;

class Request
{
    public function getPath(): string
    {
        return $_SERVER['REQUEST_URI'] ? strtok($_SERVER['REQUEST_URI'], '?') : '/';
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getInputs(): array
    {
        return $_REQUEST ?? [];
    }

    public function getInput(string $key, $default = null): string
    {
        $inputs = $this->getInputs();
        return $inputs[$key] ?? $default;
    }
}

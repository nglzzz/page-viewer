<?php

declare(strict_types=1);

namespace Core;

class Config
{
    private array $config;

    public function __construct(string $path, array $config = [])
    {
        $this->config = array_merge($this->parseEnvFile($path), $config);
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    private function parseEnvFile(string $path): array
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $vars = [];
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            [$name, $value] = explode('=', $line, 2);
            $vars[$name] = $value;
        }
        return $vars;
    }
}

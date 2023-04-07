<?php

declare(strict_types=1);

namespace Core;

class Response
{
    public function setHeader(string $name, string $value): void
    {
        header("$name: $value");
    }

    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function send($body): void
    {
        echo $body;
    }
}

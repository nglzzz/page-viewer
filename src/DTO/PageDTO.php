<?php

declare(strict_types=1);

namespace App\DTO;

class PageDTO
{
    public string $mime;
    public string $title;
    public string $text;

    public function __construct(string $title, string $text, string $mime)
    {
        $this->title = $title;
        $this->text = $text;
        $this->mime = $mime;
    }
}

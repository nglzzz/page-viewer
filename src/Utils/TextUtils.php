<?php

declare(strict_types=1);

namespace App\Utils;

class TextUtils
{
    public function convertTextToHtml(string $text): string
    {
        $text = $this->convertLinksToHtml($text);
        $text = $this->convertEmailAddressesToHtml($text);
        $text = $this->convertAsterisksToList($text);
        $text = $this->convertParagraphsToHeaders($text);
        $text = $this->convertNumberSignsToHeaders($text);
        $text = $this->convertTextLinesToParagraphs($text);

        return $text;
    }

    public function convertLinksToHtml(string $text): string
    {
        return preg_replace('/\b((?:https?|ftp):\/\/[^\s<]+[^\s<\.)])/i', '<a href="$1">$1</a>', $text);
    }

    public function convertEmailAddressesToHtml(string $text): string
    {
        return  preg_replace('/\b([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,})\b/i', '<a href="mailto:$1">$1</a>', $text);;
    }

    public function convertTextLinesToParagraphs(string $text): string
    {
        return preg_replace('/(\n\s*\n)([^\n]+)/', '$1<p>$2</p>', $text);
    }

    public function convertParagraphsToHeaders(string $text): string
    {
        return preg_replace('/^(.+)[\r\n]+([=-]{2,})[\r\n]+/m', '<h1>$1</h1>', $text);
    }

    public function convertNumberSignsToHeaders(string $text): string
    {
        return preg_replace_callback('/^(#{2,})\s*(.+)$/m', static function ($matches) {
            $level = strlen($matches[1]);
            $text = trim($matches[2]);
            return "<h$level>$text</h$level>";
        }, $text);
    }

    public function convertAsterisksToList(string $text): string
    {
        $text = preg_replace("/\-+(.*)?/i","<ul><li>$1</li></ul>",$text);
        return preg_replace("/(\<\/ul\>\n(.*)\<ul\>*)+/","",$text);
    }
}

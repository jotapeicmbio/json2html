<?php

namespace Icmbio\Json2html;

interface Json2HtmlInterface
{
    public static function make(string|array $content): self;
    public function title(): self;
    public function table(): self;
    public function get(): string;
}
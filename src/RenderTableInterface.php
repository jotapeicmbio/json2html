<?php

namespace Icmbio\Json2html;

interface RenderTableInterface
{
    public function make(array $dataset): self;
    public function titles(array $headers): self;
    public function config(array $set): self;
    public function render(): string;
}

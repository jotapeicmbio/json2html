<?php

namespace Icmbio\Json2html;

interface RenderTableInterface
{
    public static function make(array $dataset): static;
    public function titles(array $headers): self;
    public function body(array $dataset): self;
    public function config(array $set): self;
    public function nestedArrayStrategy(NestedArrayRenderStrategyInterface $strategy): self;
    public function tableClass(string $class, bool $nested = true): self;
    public function tableId(string $id, bool $nested = false): self;
    public function tableBorder(int $border, bool $nested = true): self;
    public function tableAttribute(string $name, string $value, bool $nested = true): self;
    public function render(): string;
}

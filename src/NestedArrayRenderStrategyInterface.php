<?php

namespace Icmbio\Json2html;

use DOMNode;

interface NestedArrayRenderStrategyInterface
{
    public function render(AbstractRenderer $renderer, array $data): DOMNode;
}

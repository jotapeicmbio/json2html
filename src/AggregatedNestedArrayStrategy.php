<?php

namespace Icmbio\Json2html;

use DOMNode;

class AggregatedNestedArrayStrategy implements NestedArrayRenderStrategyInterface
{
    public function render(AbstractRenderer $renderer, array $data): DOMNode
    {
        return $renderer->renderNestedArrayAsSingleTable($data);
    }
}

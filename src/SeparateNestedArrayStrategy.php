<?php

namespace Icmbio\Json2html;

use DOMNode;

class SeparateNestedArrayStrategy implements NestedArrayRenderStrategyInterface
{
    public function render(AbstractRenderer $renderer, array $data): DOMNode
    {
        if ($renderer->isAssociativeData($data)) {
            return $renderer->renderNestedArrayAsSingleTable($data);
        }

        if (!$renderer->containsNestedArrays($data)) {
            return $renderer->renderNestedArrayAsSingleTable($data);
        }

        $fragment = $renderer->createFragment();

        foreach ($data as $item) {
            if (is_array($item)) {
                $fragment->appendChild($renderer->renderNestedArrayAsSingleTable($item));
                continue;
            }

            $fragment->appendChild($renderer->renderNestedArrayAsSingleTable([$item]));
        }

        return $fragment;
    }
}

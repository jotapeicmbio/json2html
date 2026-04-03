<?php

namespace Icmbio\Json2html;

class TableHorizontalSeparate extends TablePreset
{
    protected function boot(): void
    {
        $this->config([
            'orientation' => TableOrientation::HORIZONTAL,
            'nested' => TableOrientation::HORIZONTAL,
        ]);

        $this->nestedArrayStrategy(new SeparateNestedArrayStrategy());
    }
}

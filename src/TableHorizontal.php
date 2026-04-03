<?php

namespace Icmbio\Json2html;

class TableHorizontal extends TablePreset
{
    protected function boot(): void
    {
        $this->config([
            'orientation' => TableOrientation::HORIZONTAL,
            'nested' => TableOrientation::HORIZONTAL,
        ]);

        $this->nestedArrayStrategy(new AggregatedNestedArrayStrategy());
    }
}

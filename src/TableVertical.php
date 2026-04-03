<?php

namespace Icmbio\Json2html;

class TableVertical extends TablePreset
{
    protected function boot(): void
    {
        $this->config([
            'orientation' => TableOrientation::VERTICAL,
            'nested' => TableOrientation::VERTICAL,
        ]);

        $this->nestedArrayStrategy(new AggregatedNestedArrayStrategy());
    }
}

<?php

namespace Icmbio\Json2html;

class TableVerticalSeparate extends TablePreset
{
    protected function boot(): void
    {
        $this->config([
            'orientation' => TableOrientation::VERTICAL,
            'nested' => TableOrientation::VERTICAL,
        ]);

        $this->nestedArrayStrategy(new SeparateNestedArrayStrategy());
    }
}

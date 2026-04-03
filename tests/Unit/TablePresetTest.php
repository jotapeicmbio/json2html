<?php

namespace Test\Unit;

use Icmbio\Json2html\TableHorizontal;
use Icmbio\Json2html\TableVertical;
use Icmbio\Json2html\TableVerticalSeparate;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TablePresetTest extends TestCase
{
    #[Test]
    final public function horizontalPresetShouldRenderUsingDefaultHorizontalSemantics()
    {
        $dataset = [
            'name' => 'json2html',
            'description' => 'Converts JSON to HTML tabular representation',
        ];

        $expected = '<table><thead><tr><th>name</th><th>description</th></tr></thead><tbody><tr><td>json2html</td><td>Converts JSON to HTML tabular representation</td></tr></tbody></table>';

        $table = TableHorizontal::make($dataset)->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function verticalPresetShouldPreserveAggregatedNestedSemantics()
    {
        $dataset = [
            'Monitor' => [
                ['Nome' => 'Luiz Loureiro '],
                ['Nome' => 'Michele Rocha Silva '],
            ],
        ];

        $expected = '<table><tbody><tr><td>Monitor</td><td><table><tbody><tr><td>Nome</td><td>Luiz Loureiro </td></tr><tr><td>Nome</td><td>Michele Rocha Silva </td></tr></tbody></table></td></tr></tbody></table>';

        $table = TableVertical::make($dataset)->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function verticalSeparatePresetShouldRenderEachNestedArrayItemAsItsOwnTable()
    {
        $dataset = [
            'Items' => [
                [
                    'Name' => 'Item A',
                ],
                [
                    'Name' => 'Item B',
                ],
            ],
        ];

        $expected = '<table><tbody><tr><td>Items</td><td><table><tbody><tr><td>Name</td><td>Item A</td></tr></tbody></table><table><tbody><tr><td>Name</td><td>Item B</td></tr></tbody></table></td></tr></tbody></table>';

        $table = TableVerticalSeparate::make($dataset)->render();

        $this->assertEquals($expected, $table);
    }
}

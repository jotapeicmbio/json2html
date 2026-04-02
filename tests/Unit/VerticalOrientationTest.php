<?php

namespace Test\Unit;

use Icmbio\Json2html\RenderTable;
use Icmbio\Json2html\TableOrientation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class VerticalOrientationTest extends TestCase
{
    #[Test]
    final public function simpleDatasetShouldRenderVerticallyWhenConfigured()
    {
        $dataset = [
            "name" => "json2html",
            "description" => "Converts JSON to HTML tabular representation"
        ];

        $expected = "<table><tbody><tr><td>name</td><td>json2html</td></tr><tr><td>description</td><td>Converts JSON to HTML tabular representation</td></tr></tbody></table>";

        $table = (new RenderTable($dataset))
            ->config(['orientation' => TableOrientation::VERTICAL])
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function multidimensionalDatasetShouldRenderVerticallyWhenConfigured()
    {
        $dataset = [
            "Linguagens" => ["PHP", "JS", "CSS"],
            "Banco de dados" => ["Postgres", "MySQL", "SQLite"]
        ];

        $expected = "<table><tbody><tr><td>Linguagens</td><td>PHP</td><td>JS</td><td>CSS</td></tr><tr><td>Banco de dados</td><td>Postgres</td><td>MySQL</td><td>SQLite</td></tr></tbody></table>";

        $table = (new RenderTable($dataset))
            ->config(['orientation' => TableOrientation::VERTICAL])
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function nestedDataShouldFollowNestedOrientationConfiguration()
    {
        $dataset = [
            "Monitor" => [
                ["Nome" => "Luiz Loureiro "],
                ["Nome" => "Michele Rocha Silva "]
            ]
        ];

        $expected = "<table><tbody><tr><td>Monitor</td><td><table><tbody><tr><td>Nome</td><td>Luiz Loureiro </td></tr><tr><td>Nome</td><td>Michele Rocha Silva </td></tr></tbody></table></td></tr></tbody></table>";

        $table = (new RenderTable($dataset))
            ->config([
                'orientation' => TableOrientation::VERTICAL,
                'nested' => TableOrientation::VERTICAL
            ])
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function mixedOrientationShouldWork()
    {
        $dataset = [
            "Monitor" => [
                ["Nome" => "Luiz Loureiro "],
                ["Nome" => "Michele Rocha Silva "]
            ]
        ];

        // Main vertical, nested horizontal
        $expected = "<table><tbody><tr><td>Monitor</td><td><table><thead><tr><th>Nome</th></tr></thead><tbody><tr><td>Luiz Loureiro </td></tr><tr><td>Michele Rocha Silva </td></tr></tbody></table></td></tr></tbody></table>";

        $table = (new RenderTable($dataset))
            ->config([
                'orientation' => TableOrientation::VERTICAL,
                'nested' => TableOrientation::HORIZONTAL
            ])
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function backwardsCompatibilityShouldMaintainHorizontalAsDefault()
    {
        $dataset = [
            "name" => "json2html",
            "description" => "Converts JSON to HTML tabular representation"
        ];

        $expected = "<table><thead><tr><th>name</th><th>description</th></tr></thead><tbody><tr><td>json2html</td><td>Converts JSON to HTML tabular representation</td></tr></tbody></table>";

        // Without config, should default to horizontal
        $table = (new RenderTable($dataset))->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function nestedArraysInsideArrayOfObjectsShouldRenderVertically()
    {
        $dataset = [
            'Items' => [
                [
                    'Name' => 'Item A',
                    'Details' => [
                        ['Label' => 'Height', 'Value' => '10m'],
                    ],
                ],
            ],
        ];

        $table = (new RenderTable($dataset))
            ->config([
                'orientation' => TableOrientation::VERTICAL,
                'nested' => TableOrientation::VERTICAL,
            ])
            ->render();

        $this->assertStringContainsString('Items', $table);
        $this->assertStringContainsString('Details', $table);
        $this->assertStringContainsString('Height', $table);
        $this->assertStringContainsString('10m', $table);
    }
}

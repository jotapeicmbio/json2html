<?php

namespace Test\Unit;

use Icmbio\Json2html\RenderTable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RenderTableTest extends TestCase
{
    #[Test]
    final public function withoutADatasetItShouldReturnAnEmptyHTMLTable()
    {
        $expected = "<table></table>";
        $this->assertEquals($expected, (new RenderTable)->render());
    }

    #[Test]
    final public function passingASimpleDatasetShouldReturnASimpleHTMLTable()
    {
        $dataset = [
            "name" => "json2html",
            "description" => "Converts JSON to HTML tabular representation"
        ];

        $expected = "<table><thead><tr><th>name</th><th>description</th></tr></thead><tbody><tr><td>json2html</td><td>Converts JSON to HTML tabular representation</td></tr></tbody></table>";

        $table = (new RenderTable($dataset))->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function passingAMultidimensionalOneLevelDatasetShouldReturnATableHTML()
    {
        $dataset = [
            "Linguagens" => ["PHP", "JS", "CSS"],
            "Banco de dados" => ["Postgres", "MySQL", "SQLite"]
        ];

        $expected = "<table><thead><tr><th>Linguagens</th><th>Banco de dados</th></tr></thead><tbody><tr><td>PHP</td><td>Postgres</td></tr><tr><td>JS</td><td>MySQL</td></tr><tr><td>CSS</td><td>SQLite</td></tr></tbody></table>";

        $table = (new RenderTable($dataset))->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function passingAMultidimensionalTwoLevelDatasetShouldReturnATableHTML()
    {
        $dataset = [
            "Ambiente" => "Mata Atlântica",
            "Monitor" => [
                ["Nome" => "Luiz Loureiro "],
                ["Nome" => "Michele Rocha Silva "]
            ],
            "Unidade Amostral" => "3981",
            "Existem plantas dentro do critério de inclusão?" => "Sim",
            "Planta" => [
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ],
                [
                    "Tipo da Planta" => "",
                    "Teve coleta botânica?" => "Não",
                    "Como você mapeará a planta?" => "x, y",
                ]
            ]
        ];

        $expected = "<table><thead><tr><th>Ambiente</th><th>Monitor</th><th>Unidade Amostral</th><th>Existem plantas dentro do critério de inclusão?</th><th>Planta</th></tr></thead><tbody><tr><td>Mata Atlântica</td><td><table><thead><tr><th>Nome</th></tr></thead><tbody><tr><td>Luiz Loureiro </td></tr><tr><td>Michele Rocha Silva </td></tr></tbody></table></td><td>3981</td><td>Sim</td><td><table><thead><tr><th>Tipo da Planta</th><th>Teve coleta botânica?</th><th>Como você mapeará a planta?</th></tr></thead><tbody><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr><tr><td></td><td>Não</td><td>x, y</td></tr></tbody></table></td></tr></tbody></table>";

        $table = (new RenderTable($dataset))->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function nestedArraysInsideArrayOfObjectsShouldRenderHorizontally()
    {
        $dataset = [
            'People' => [
                [
                    'Name' => 'Ana',
                    'Phones' => [
                        ['Type' => 'Work', 'Number' => '1111-1111'],
                    ],
                    'Dimensions' => [
                        'Height' => '80cm',
                        'Width' => '120cm',
                    ],
                ],
            ],
        ];

        $table = (new RenderTable($dataset))->render();

        $this->assertStringContainsString('People', $table);
        $this->assertStringContainsString('Phones', $table);
        $this->assertStringContainsString('Work', $table);
        $this->assertStringContainsString('1111-1111', $table);
        $this->assertStringContainsString('Height', $table);
        $this->assertStringContainsString('80cm', $table);
    }
}

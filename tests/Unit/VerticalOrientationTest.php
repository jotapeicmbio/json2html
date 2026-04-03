<?php

namespace Test\Unit;

use Icmbio\Json2html\RenderTable;
use Icmbio\Json2html\TableVerticalSeparate;
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

    #[Test]
    final public function nestedArrayShouldRenderNestedTableVertically()
    {
        $dataset = [
            "starttime" => "2026-03-13T19:20:33.232-03:00",
            "endtime" => "2026-03-13T19:44:11.778-03:00",
            "Unidade de Conservação" => "137",
            "Estação Amostral" => "658",
            "Unidade Amostral" => "3981",
            "Planta" => [
                [
                "Número da plaqueta da Planta" => 231,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 56.2,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 12.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.7,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 40.4,
                "Foto da Planta" => "",
                "uuid" => "9131de2c-4355-4a55-9c3d-8ea07f30cfac",
                ],
                [
                "Número da plaqueta da Planta" => 232,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 40.2,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 8.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.6,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 41.4,
                "Foto da Planta" => "",
                "uuid" => "2bec8a34-6656-4cbd-b76b-3efbcd682a6f",
                ],
                [
                "Número da plaqueta da Planta" => 233,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 43.1,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 14.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.8,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 45.0,
                "Foto da Planta" => "",
                "uuid" => "09141419-f4af-4be5-bbbb-feaec68aa593",
                ],
            ],
            "Foto do croqui da parcela" => "",
            "Dado mascarado" => "",
            "Campo sigiloso" => "",
            "Dado sigiloso" => "",
            "instanceID" => "uuid:c22bb4b4-3874-43c7-b1a4-1ad4d62c3521",
        ];

        $table = TableVerticalSeparate::make($dataset)->render();

        $expected = "<table><tbody><tr><td>starttime</td><td>2026-03-13T19:20:33.232-03:00</td></tr><tr><td>endtime</td><td>2026-03-13T19:44:11.778-03:00</td></tr><tr><td>Unidade de Conservação</td><td>137</td></tr><tr><td>Estação Amostral</td><td>658</td></tr><tr><td>Unidade Amostral</td><td>3981</td></tr><tr><td>Planta</td><td><table><tbody><tr><td>Número da plaqueta da Planta</td><td>231</td></tr><tr><td>Essa planta é nova na amostragem?</td><td>Sim</td></tr><tr><td>Circunferência à altura do peito</td><td><table><tbody><tr><td>CAP</td><td>56.2</td></tr></tbody></table></td></tr><tr><td>Circunferência à 30 cm do solo</td><td><table><tbody><tr><td>CAS</td><td></td></tr></tbody></table></td></tr><tr><td>O CAP foi medido à 1.30m de altura?</td><td>Sim</td></tr><tr><td>Altura total da planta (m)</td><td>12</td></tr><tr><td>Como você mediu a altura da planta?</td><td>Estimada</td></tr><tr><td>Tipo da Planta</td><td>Árvore</td></tr><tr><td>Planta morta em pé com altura maior do que 1,30m?</td><td>Não</td></tr><tr><td>Teve coleta botânica?</td><td>Não</td></tr><tr><td>Observação do registro</td><td></td></tr><tr><td>Como você mapeará a planta?</td><td>x, y</td></tr><tr><td>X (distância da planta no eixo X)</td><td>0.7</td></tr><tr><td>Esse número é negativo?</td><td>nao</td></tr><tr><td>Y (distância da planta no eixo Y)</td><td>40.4</td></tr><tr><td>Foto da Planta</td><td></td></tr><tr><td>uuid</td><td>9131de2c-4355-4a55-9c3d-8ea07f30cfac</td></tr></tbody></table><table><tbody><tr><td>Número da plaqueta da Planta</td><td>232</td></tr><tr><td>Essa planta é nova na amostragem?</td><td>Sim</td></tr><tr><td>Circunferência à altura do peito</td><td><table><tbody><tr><td>CAP</td><td>40.2</td></tr></tbody></table></td></tr><tr><td>Circunferência à 30 cm do solo</td><td><table><tbody><tr><td>CAS</td><td></td></tr></tbody></table></td></tr><tr><td>O CAP foi medido à 1.30m de altura?</td><td>Sim</td></tr><tr><td>Altura total da planta (m)</td><td>8</td></tr><tr><td>Como você mediu a altura da planta?</td><td>Estimada</td></tr><tr><td>Tipo da Planta</td><td>Árvore</td></tr><tr><td>Planta morta em pé com altura maior do que 1,30m?</td><td>Não</td></tr><tr><td>Teve coleta botânica?</td><td>Não</td></tr><tr><td>Observação do registro</td><td></td></tr><tr><td>Como você mapeará a planta?</td><td>x, y</td></tr><tr><td>X (distância da planta no eixo X)</td><td>0.6</td></tr><tr><td>Esse número é negativo?</td><td>nao</td></tr><tr><td>Y (distância da planta no eixo Y)</td><td>41.4</td></tr><tr><td>Foto da Planta</td><td></td></tr><tr><td>uuid</td><td>2bec8a34-6656-4cbd-b76b-3efbcd682a6f</td></tr></tbody></table><table><tbody><tr><td>Número da plaqueta da Planta</td><td>233</td></tr><tr><td>Essa planta é nova na amostragem?</td><td>Sim</td></tr><tr><td>Circunferência à altura do peito</td><td><table><tbody><tr><td>CAP</td><td>43.1</td></tr></tbody></table></td></tr><tr><td>Circunferência à 30 cm do solo</td><td><table><tbody><tr><td>CAS</td><td></td></tr></tbody></table></td></tr><tr><td>O CAP foi medido à 1.30m de altura?</td><td>Sim</td></tr><tr><td>Altura total da planta (m)</td><td>14</td></tr><tr><td>Como você mediu a altura da planta?</td><td>Estimada</td></tr><tr><td>Tipo da Planta</td><td>Árvore</td></tr><tr><td>Planta morta em pé com altura maior do que 1,30m?</td><td>Não</td></tr><tr><td>Teve coleta botânica?</td><td>Não</td></tr><tr><td>Observação do registro</td><td></td></tr><tr><td>Como você mapeará a planta?</td><td>x, y</td></tr><tr><td>X (distância da planta no eixo X)</td><td>0.8</td></tr><tr><td>Esse número é negativo?</td><td>nao</td></tr><tr><td>Y (distância da planta no eixo Y)</td><td>45</td></tr><tr><td>Foto da Planta</td><td></td></tr><tr><td>uuid</td><td>09141419-f4af-4be5-bbbb-feaec68aa593</td></tr></tbody></table></td></tr><tr><td>Foto do croqui da parcela</td><td></td></tr><tr><td>Dado mascarado</td><td></td></tr><tr><td>Campo sigiloso</td><td></td></tr><tr><td>Dado sigiloso</td><td></td></tr><tr><td>instanceID</td><td>uuid:c22bb4b4-3874-43c7-b1a4-1ad4d62c3521</td></tr></tbody></table>";

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function nestedArrayShouldRenderNestedTableHorizontally()
    {
        $dataset = [
            "starttime" => "2026-03-13T19:20:33.232-03:00",
            "endtime" => "2026-03-13T19:44:11.778-03:00",
            "Unidade de Conservação" => "137",
            "Estação Amostral" => "658",
            "Unidade Amostral" => "3981",
            "Planta" => [
                [
                "Número da plaqueta da Planta" => 231,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 56.2,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 12.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.7,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 40.4,
                "Foto da Planta" => "",
                "uuid" => "9131de2c-4355-4a55-9c3d-8ea07f30cfac",
                ],
                [
                "Número da plaqueta da Planta" => 232,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 40.2,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 8.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.6,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 41.4,
                "Foto da Planta" => "",
                "uuid" => "2bec8a34-6656-4cbd-b76b-3efbcd682a6f",
                ],
                [
                "Número da plaqueta da Planta" => 233,
                "Essa planta é nova na amostragem?" => "Sim",
                "Circunferência à altura do peito" => [
                    [
                    "CAP" => 43.1,
                    ],
                ],
                "Circunferência à 30 cm do solo" => [
                    [
                    "CAS" => "",
                    ],
                ],
                "O CAP foi medido à 1.30m de altura?" => "Sim",
                "Altura total da planta (m)" => 14.0,
                "Como você mediu a altura da planta?" => "Estimada",
                "Tipo da Planta" => "Árvore",
                "Planta morta em pé com altura maior do que 1,30m?" => "Não",
                "Teve coleta botânica?" => "Não",
                "Observação do registro" => "",
                "Como você mapeará a planta?" => "x, y",
                "X (distância da planta no eixo X)" => 0.8,
                "Esse número é negativo?" => "nao",
                "Y (distância da planta no eixo Y)" => 45.0,
                "Foto da Planta" => "",
                "uuid" => "09141419-f4af-4be5-bbbb-feaec68aa593",
                ],
            ],
            "Foto do croqui da parcela" => "",
            "Dado mascarado" => "",
            "Campo sigiloso" => "",
            "Dado sigiloso" => "",
            "instanceID" => "uuid:c22bb4b4-3874-43c7-b1a4-1ad4d62c3521",
        ];

        $table = (new RenderTable($dataset))
            ->render();

        $expected = "<table><thead><tr><th>starttime</th><th>endtime</th><th>Unidade de Conservação</th><th>Estação Amostral</th><th>Unidade Amostral</th><th>Planta</th><th>Foto do croqui da parcela</th><th>Dado mascarado</th><th>Campo sigiloso</th><th>Dado sigiloso</th><th>instanceID</th></tr></thead><tbody><tr><td>2026-03-13T19:20:33.232-03:00</td><td>2026-03-13T19:44:11.778-03:00</td><td>137</td><td>658</td><td>3981</td><td><table><thead><tr><th>Número da plaqueta da Planta</th><th>Essa planta é nova na amostragem?</th><th>Circunferência à altura do peito</th><th>Circunferência à 30 cm do solo</th><th>O CAP foi medido à 1.30m de altura?</th><th>Altura total da planta (m)</th><th>Como você mediu a altura da planta?</th><th>Tipo da Planta</th><th>Planta morta em pé com altura maior do que 1,30m?</th><th>Teve coleta botânica?</th><th>Observação do registro</th><th>Como você mapeará a planta?</th><th>X (distância da planta no eixo X)</th><th>Esse número é negativo?</th><th>Y (distância da planta no eixo Y)</th><th>Foto da Planta</th><th>uuid</th></tr></thead><tbody><tr><td>231</td><td>Sim</td><td><table><thead><tr><th>CAP</th></tr></thead><tbody><tr><td>56.2</td></tr></tbody></table></td><td><table><thead><tr><th>CAS</th></tr></thead><tbody><tr><td></td></tr></tbody></table></td><td>Sim</td><td>12</td><td>Estimada</td><td>Árvore</td><td>Não</td><td>Não</td><td></td><td>x, y</td><td>0.7</td><td>nao</td><td>40.4</td><td></td><td>9131de2c-4355-4a55-9c3d-8ea07f30cfac</td></tr><tr><td>232</td><td>Sim</td><td><table><thead><tr><th>CAP</th></tr></thead><tbody><tr><td>40.2</td></tr></tbody></table></td><td><table><thead><tr><th>CAS</th></tr></thead><tbody><tr><td></td></tr></tbody></table></td><td>Sim</td><td>8</td><td>Estimada</td><td>Árvore</td><td>Não</td><td>Não</td><td></td><td>x, y</td><td>0.6</td><td>nao</td><td>41.4</td><td></td><td>2bec8a34-6656-4cbd-b76b-3efbcd682a6f</td></tr><tr><td>233</td><td>Sim</td><td><table><thead><tr><th>CAP</th></tr></thead><tbody><tr><td>43.1</td></tr></tbody></table></td><td><table><thead><tr><th>CAS</th></tr></thead><tbody><tr><td></td></tr></tbody></table></td><td>Sim</td><td>14</td><td>Estimada</td><td>Árvore</td><td>Não</td><td>Não</td><td></td><td>x, y</td><td>0.8</td><td>nao</td><td>45</td><td></td><td>09141419-f4af-4be5-bbbb-feaec68aa593</td></tr></tbody></table></td><td></td><td></td><td></td><td></td><td>uuid:c22bb4b4-3874-43c7-b1a4-1ad4d62c3521</td></tr></tbody></table>";

        $this->assertEquals($expected, $table);
    }
}

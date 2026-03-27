<?php

namespace Test\Unit;

use Icmbio\Json2html\RenderTable;
use Icmbio\Json2html\TableOrientation;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TableAttributesTest extends TestCase
{
    #[Test]
    final public function shouldAddClassAttributeToTable()
    {
        $dataset = [
            "name" => "json2html",
            "description" => "Converts JSON to HTML"
        ];

        $expected = '<table class="table table-bordered"><thead><tr><th>name</th><th>description</th></tr></thead><tbody><tr><td>json2html</td><td>Converts JSON to HTML</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableClass('table table-bordered')
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldAddIdAttributeToTable()
    {
        $dataset = [
            "name" => "json2html"
        ];

        $expected = '<table id="info-table"><thead><tr><th>name</th></tr></thead><tbody><tr><td>json2html</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableId('info-table')
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldAddBorderAttributeToTable()
    {
        $dataset = [
            "name" => "json2html"
        ];

        $expected = '<table border="1"><thead><tr><th>name</th></tr></thead><tbody><tr><td>json2html</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableBorder(1)
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldAddMultipleClassesByChaining()
    {
        $dataset = [
            "name" => "json2html"
        ];

        $expected = '<table class="table table-bordered table-hover"><thead><tr><th>name</th></tr></thead><tbody><tr><td>json2html</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableClass('table')
            ->tableClass('table-bordered')
            ->tableClass('table-hover')
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldAddCustomAttributeToTable()
    {
        $dataset = [
            "name" => "json2html"
        ];

        $expected = '<table data-test="custom"><thead><tr><th>name</th></tr></thead><tbody><tr><td>json2html</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableAttribute('data-test', 'custom')
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldApplyAttributesToNestedTablesByDefault()
    {
        $dataset = [
            "Monitor" => [
                ["Nome" => "Luiz Loureiro"],
                ["Nome" => "Michele Rocha Silva"]
            ]
        ];

        $expected = '<table class="table"><thead><tr><th>Monitor</th></tr></thead><tbody><tr><td><table class="table"><thead><tr><th>Nome</th></tr></thead><tbody><tr><td>Luiz Loureiro</td></tr><tr><td>Michele Rocha Silva</td></tr></tbody></table></td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableClass('table')  // nested=true por padrão
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldNotApplyIdToNestedTablesByDefault()
    {
        $dataset = [
            "Monitor" => [
                ["Nome" => "Luiz Loureiro"]
            ]
        ];

        $expected = '<table id="main-table"><thead><tr><th>Monitor</th></tr></thead><tbody><tr><td><table><thead><tr><th>Nome</th></tr></thead><tbody><tr><td>Luiz Loureiro</td></tr></tbody></table></td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableId('main-table')  // nested=false por padrão
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldControlNestedApplicationWithParameter()
    {
        $dataset = [
            "Monitor" => [
                ["Nome" => "Luiz Loureiro"]
            ]
        ];

        $expected = '<table class="root-only"><thead><tr><th>Monitor</th></tr></thead><tbody><tr><td><table><thead><tr><th>Nome</th></tr></thead><tbody><tr><td>Luiz Loureiro</td></tr></tbody></table></td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableClass('root-only', nested: false)
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldWorkWithVerticalOrientation()
    {
        $dataset = [
            "name" => "json2html",
            "description" => "Converts JSON to HTML"
        ];

        $expected = '<table class="vertical-table"><tbody><tr><td>name</td><td>json2html</td></tr><tr><td>description</td><td>Converts JSON to HTML</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->config(['orientation' => TableOrientation::VERTICAL])
            ->tableClass('vertical-table')
            ->render();

        $this->assertEquals($expected, $table);
    }

    #[Test]
    final public function shouldCombineMultipleAttributes()
    {
        $dataset = [
            "name" => "json2html"
        ];

        $expected = '<table id="info-table" class="table table-bordered" border="1" data-test="combined"><thead><tr><th>name</th></tr></thead><tbody><tr><td>json2html</td></tr></tbody></table>';

        $table = (new RenderTable($dataset))
            ->tableId('info-table')
            ->tableClass('table table-bordered')
            ->tableBorder(1)
            ->tableAttribute('data-test', 'combined')
            ->render();

        $this->assertEquals($expected, $table);
    }
}
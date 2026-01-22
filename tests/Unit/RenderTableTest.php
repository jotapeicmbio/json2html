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
}
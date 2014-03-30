<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\TableFilter;
use Decoda\Test\TestCase;

class TableFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new TableFilter());
    }

    /**
     * Test the [table] tag.
     */
    public function testTable() {
        $this->assertEquals('<table class="decoda-table"></table>', $this->object->reset('[table]Table[/table]')->parse());
        $this->assertEquals('<table class="decoda-table test"></table>', $this->object->reset('[table class="test"]Table[/table]')->parse());
    }

    /**
     * Test the [thead] tag.
     */
    public function testThead() {
        $this->assertEquals('<table class="decoda-table"><thead></thead></table>', $this->object->reset('[table][thead]Table[/thead][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[thead]Table[/thead]')->parse());
    }

    /**
     * Test the [tbody] tag.
     */
    public function testTbody() {
        $this->assertEquals('<table class="decoda-table"><tbody></tbody></table>', $this->object->reset('[table][tbody]Table[/tbody][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[tbody]Table[/tbody]')->parse());
    }

    /**
     * Test the [tfoot] tag.
     */
    public function testTfoot() {
        $this->assertEquals('<table class="decoda-table"><tfoot></tfoot></table>', $this->object->reset('[table][tfoot]Table[/tfoot][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[tfoot]Table[/tfoot]')->parse());
    }

    /**
     * Test the [tr] tag.
     */
    public function testTr() {
        $this->assertEquals('<table class="decoda-table"><tr></tr></table>', $this->object->reset('[table][tr]Table[/tr][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[tr]Table[/tr]')->parse());
    }

    /**
     * Test the [th] tag.
     */
    public function testTh() {
        $this->assertEquals('<table class="decoda-table"><tr><th>One</th><th>Two</th></tr></table>', $this->object->reset('[table][tr][th]One[/th][th]Two[/th][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><th>One</th><th>Two</th></tr><tr><td>One</td><td>Two</td></tr></table>', $this->object->reset('[table][tr][th]One[/th][th]Two[/th][/tr][tr][td]One[/td][td]Two[/td][/tr][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[th]Table[/th]')->parse());
        $this->assertEquals('Table', $this->object->reset('[tr][th]Table[/th][/tr]')->parse());

        // colspan & rowspan
        $this->assertEquals('<table class="decoda-table"><tr><th colspan="2">Two</th></tr></table>', $this->object->reset('[table][tr][th="2"]Two[/th][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><th colspan="3">Three</th></tr></table>', $this->object->reset('[table][tr][th cols="3"]Three[/th][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><th colspan="3" rowspan="4">Four</th></tr></table>', $this->object->reset('[table][tr][th cols="3" rows="4"]Four[/th][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><th rowspan="5">Five</th></tr></table>', $this->object->reset('[table][tr][th rows="5"]Five[/th][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><th>Two</th></tr></table>', $this->object->reset('[table][tr][th="abc"]Two[/th][/tr][/table]')->parse());
    }

    /**
     * Test the [td] tag.
     */
    public function testTd() {
        $this->assertEquals('<table class="decoda-table"><tr><td>One</td><td>Two</td></tr></table>', $this->object->reset('[table][tr][td]One[/td][td]Two[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td>One</td><td>Two</td></tr><tr><td>One</td><td>Two</td></tr></table>', $this->object->reset('[table][tr][td]One[/td][td]Two[/td][/tr][tr][td]One[/td][td]Two[/td][/tr][/table]')->parse());
        $this->assertEquals('Table', $this->object->reset('[td]Table[/td]')->parse());
        $this->assertEquals('Table', $this->object->reset('[tr][td]Table[/td][/tr]')->parse());

        // colspan & rowspan
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="2">Two</td></tr></table>', $this->object->reset('[table][tr][td="2"]Two[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="3">Three</td></tr></table>', $this->object->reset('[table][tr][td cols="3"]Three[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="3" rowspan="4">Four</td></tr></table>', $this->object->reset('[table][tr][td cols="3" rows="4"]Four[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td rowspan="5">Five</td></tr></table>', $this->object->reset('[table][tr][td rows="5"]Five[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td>Two</td></tr></table>', $this->object->reset('[table][tr][td="abc"]Two[/td][/tr][/table]')->parse());
    }

    /**
     * Test tag aliasing.
     */
    public function testAliases() {
        $this->assertEquals('<table class="decoda-table"><tr><td>One</td><td>Two</td></tr></table>', $this->object->reset('[table][row][col]One[/col][col]Two[/col][/row][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td>One</td><td>Two</td></tr><tr><td>One</td><td>Two</td></tr></table>', $this->object->reset('[table][row][col]One[/col][col]Two[/col][/row][row][col]One[/col][col]Two[/col][/row][/table]')->parse());

        // colspan & rowspan
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="2">Two</td></tr></table>', $this->object->reset('[table][tr][td="2"]Two[/td][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="3">Three</td></tr></table>', $this->object->reset('[table][row][col colspan="3"]Three[/col][/row][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td colspan="3" rowspan="4">Four</td></tr></table>', $this->object->reset('[table][row][td cols="3" rowspan="4"]Four[/td][/row][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td rowspan="5">Five</td></tr></table>', $this->object->reset('[table][tr][col rows="5"]Five[/col][/tr][/table]')->parse());
        $this->assertEquals('<table class="decoda-table"><tr><td>Two</td></tr></table>', $this->object->reset('[table][tr][col="abc"]Two[/col][/tr][/table]')->parse());
    }

    /**
     * Test larger examples.
     */
    public function testFullExamples() {
        $string = <<<TABLE
[table]
    [tbody]
        [tr]
            [td]One[/td]
            [td]Two[/td]
        [/tr]
        [tr]
            [td]One[/td]
            [td]Two[/td]
        [/tr]
    [/tbody]
[/table]
TABLE;

        $expected = '<table class="decoda-table"><tbody><tr><td>One</td><td>Two</td></tr><tr><td>One</td><td>Two</td></tr></tbody></table>';
        $this->assertEquals($expected, $this->object->reset($string)->parse());

        $string = <<<TABLE
[table]
    [thead]
        [tr]
            [th]One[/th]
            [th]Two[/th]
        [/tr]
    [/thead]
    [tbody]
        [tr]
            [td]One[/td]
            [td]Two[/td]
        [/tr]
        [tr]
            [td]One[/td]
            [td]Two[/td]
        [/tr]
    [/tbody]
[/table]
TABLE;

        $expected = '<table class="decoda-table"><thead><tr><th>One</th><th>Two</th></tr></thead><tbody><tr><td>One</td><td>Two</td></tr><tr><td>One</td><td>Two</td></tr></tbody></table>';
        $this->assertEquals($expected, $this->object->reset($string)->parse());

        // Invalid nesting
        $string = <<<TABLE
[table]
    [tbody]
        [tr]
            [td]One[/td]
            [td]Two[/td]
        [/tr]
        [td]One[/td]
        [td]Two[/td]
    [/tbody]
[/table]
TABLE;

        $expected = '<table class="decoda-table"><tbody><tr><td>One</td><td>Two</td></tr></tbody></table>';
        $this->assertEquals($expected, $this->object->reset($string)->parse());
    }

}
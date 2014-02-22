<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\TextFilter;
use Decoda\Test\TestCase;

class TextFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new TextFilter());
    }

    /**
     * Test that [font] renders a font family in a span.
     */
    public function testFont() {
        $this->assertEquals('<span>Font</span>', $this->object->reset('[font]Font[/font]')->parse());
        $this->assertEquals('<span style="font-family: Tahoma">Font</span>', $this->object->reset('[font="Tahoma"]Font[/font]')->parse());
        $this->assertEquals('<span style="font-family: Georgia, \'Times New Roman\', serif">Font</span>', $this->object->reset('[font="Georgia, \'Times New Roman\', serif"]Font[/font]')->parse());
        $this->assertEquals('<span style="font-family: Tahoma, Arial, sans-serif">Font</span>', $this->object->reset('[font="Tahoma, Arial, sans-serif"]Font[/font]')->parse());
    }

    /**
     * Test that [size] renders a font size in a span.
     */
    public function testSize() {
        $this->assertEquals('<span>Size</span>', $this->object->reset('[size]Size[/size]')->parse());
        $this->assertEquals('<span style="font-size: 10px">Size</span>', $this->object->reset('[size="10"]Size[/size]')->parse());
        $this->assertEquals('<span style="font-size: 20px">Size</span>', $this->object->reset('[size="20"]Size[/size]')->parse());
        $this->assertEquals('<span style="font-size: 29px">Size</span>', $this->object->reset('[size="29"]Size[/size]')->parse());

        // invalid, out of range
        $this->assertEquals('<span>Size</span>', $this->object->reset('[size="ten"]Size[/size]')->parse());
        $this->assertEquals('<span>Size</span>', $this->object->reset('[size="9"]Size[/size]')->parse());
        $this->assertEquals('<span>Size</span>', $this->object->reset('[size="30"]Size[/size]')->parse());
    }

    /**
     * Test that [color] renders a color in a span.
     */
    public function testColor() {
        $this->assertEquals('<span>Color</span>', $this->object->reset('[color]Color[/color]')->parse());
        $this->assertEquals('<span style="color: #fff">Color</span>', $this->object->reset('[color="#fff"]Color[/color]')->parse());
        $this->assertEquals('<span style="color: #000000">Color</span>', $this->object->reset('[color="#000000"]Color[/color]')->parse());
        $this->assertEquals('<span style="color: blue">Color</span>', $this->object->reset('[color="blue"]Color[/color]')->parse());

        // invalid
        $this->assertEquals('<span>Color</span>', $this->object->reset('[color="9af393"]Color[/color]')->parse());
        $this->assertEquals('<span>Color</span>', $this->object->reset('[color="66"]Color[/color]')->parse());
        $this->assertEquals('<span>Color</span>', $this->object->reset('[color="6652ssA"]Color[/color]')->parse());
    }

    /**
     * Test that [h1] - [h6] render text headings.
     */
    public function testHeaders() {
        $this->assertEquals('<h1>Header 1</h1>', $this->object->reset('[h1]Header 1[/h1]')->parse());
        $this->assertEquals('<h2>Header 2</h2>', $this->object->reset('[h2]Header 2[/h2]')->parse());
        $this->assertEquals('<h3>Header 3</h3>', $this->object->reset('[h3]Header 3[/h3]')->parse());
        $this->assertEquals('<h4>Header 4</h4>', $this->object->reset('[h4]Header 4[/h4]')->parse());
        $this->assertEquals('<h5>Header 5</h5>', $this->object->reset('[h5]Header 5[/h5]')->parse());
        $this->assertEquals('<h6>Header 6</h6>', $this->object->reset('[h6]Header 6[/h6]')->parse());
    }

}
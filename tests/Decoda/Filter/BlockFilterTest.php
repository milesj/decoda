<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\BlockFilter;
use Decoda\Test\TestCase;

class BlockFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new BlockFilter());
    }

    /**
     * Test [align] renders alignment formatting divs.
     */
    public function testAlign() {
        $this->assertEquals('<div class="align-left">Left Aligned</div>', $this->object->reset('[align="left"]Left Aligned[/align]')->parse());
        $this->assertEquals('<div class="align-right">Right Aligned</div>', $this->object->reset('[align="right"]Right Aligned[/align]')->parse());
        $this->assertEquals('<div class="align-center">Center Aligned</div>', $this->object->reset('[align="center"]Center Aligned[/align]')->parse());
        $this->assertEquals('<div class="align-justify">Justify Aligned</div>', $this->object->reset('[align="justify"]Justify Aligned[/align]')->parse());
    }

    /**
     * Test [left] renders alignment formatting divs.
     */
    public function testLeft() {
        $this->assertEquals('<div class="align-left">Left Aligned</div>', $this->object->reset('[left]Left Aligned[/left]')->parse());
    }

    /**
     * Test [right] renders alignment formatting divs.
     */
    public function testRight() {
        $this->assertEquals('<div class="align-right">Right Aligned</div>', $this->object->reset('[right]Right Aligned[/right]')->parse());
    }

    /**
     * Test [center] renders alignment formatting divs.
     */
    public function testCenter() {
        $this->assertEquals('<div class="align-center">Center Aligned</div>', $this->object->reset('[center]Center Aligned[/center]')->parse());
    }

    /**
     * Test [justify] renders alignment formatting divs.
     */
    public function testJustify() {
        $this->assertEquals('<div class="align-justify">Justify Aligned</div>', $this->object->reset('[justify]Justify Aligned[/justify]')->parse());
    }

    /**
     * Test [float] renders float formatting divs.
     */
    public function testFloat() {
        $this->assertEquals('<div class="float-left">Left Float</div>', $this->object->reset('[float="left"]Left Float[/float]')->parse());
        $this->assertEquals('<div class="float-right">Right Float</div>', $this->object->reset('[float="right"]Right Float[/float]')->parse());
        $this->assertEquals('<div class="float-none">No Float</div>', $this->object->reset('[float="none"]No Float[/float]')->parse());
    }

    /**
     * Test [hide] renders a div that hides content.
     */
    public function testHide() {
        $this->assertEquals('<span style="display: none">Hidden Text</span>', $this->object->reset('[hide]Hidden Text[/hide]')->parse());
    }

    /**
     * Test [alert] renders a div that acts as an alert.
     */
    public function testAlert() {
        $this->assertEquals('<div class="decoda-alert">Alert Box</div>', $this->object->reset('[alert]Alert Box[/alert]')->parse());
    }

    /**
     * Test [note] renders a div that acts as a note.
     */
    public function testNote() {
        $this->assertEquals('<div class="decoda-note">Note Box</div>', $this->object->reset('[note]Note Box[/note]')->parse());
    }

    /**
     * Test [div] renders a div element with an optional ID and class.
     */
    public function testDiv() {
        $this->assertEquals('<div>Div</div>', $this->object->reset('[div]Div[/div]')->parse());

        // ID
        $this->assertEquals('<div id="id-dash">Div with ID</div>', $this->object->reset('[div="id-dash"]Div with ID[/div]')->parse());
        $this->assertEquals('<div id="id_underscore">Div with ID</div>', $this->object->reset('[div="id_underscore"]Div with ID[/div]')->parse());
        $this->assertEquals('<div>Div with ID</div>', $this->object->reset('[div="id.dot"]Div with ID[/div]')->parse());
        $this->assertEquals('<div>Div with ID</div>', $this->object->reset('[div="@*#@!)#sdast"]Div with ID[/div]')->parse());

        // Class
        $this->assertEquals('<div class="class-dash">Div with class</div>', $this->object->reset('[div class="class-dash"]Div with class[/div]')->parse());
        $this->assertEquals('<div class="class_underscore">Div with class</div>', $this->object->reset('[div class="class_underscore"]Div with class[/div]')->parse());
        $this->assertEquals('<div class="class double">Div with class</div>', $this->object->reset('[div class="class double"]Div with class[/div]')->parse());
        $this->assertEquals('<div>Div with class</div>', $this->object->reset('[div class="*&0832nsas"]Div with class[/div]')->parse());

        // ID and Class
        $this->assertEquals('<div id="id" class="class">Div with ID and class</div>', $this->object->reset('[div="id" class="class"]Div with ID and class[/div]')->parse());
    }

    /**
     * Test [spoiler] renders a div element that hides content and can be made visible via a button.
     */
    public function testSpoiler() {
        $this->assertRegExp('/^\<div class="decoda-spoiler"\>(.*?)\<\/div\>$/is', $this->object->reset('[spoiler]Spoiler[/spoiler]')->parse());
    }

}
<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\ImageFilter;
use Decoda\Test\TestCase;

class ImageFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new ImageFilter());
    }

    /**
     * Test that [img] renders images with optional width, height and alt attributes.
     */
    public function testImg() {
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[image]http://domain.com/image.gif[/image]')->parse());

        // variations
        $this->assertEquals('<img src="https://domain.com/image.jpg" alt="">', $this->object->reset('[img]https://domain.com/image.jpg[/img]')->parse()); // https, jpg
        $this->assertEquals('<img src="//domain.com/image.PNG" alt="">', $this->object->reset('[img]//domain.com/image.PNG[/img]')->parse()); // no protocol, absolute png
        $this->assertEquals('<img src="../images/image.PNG" alt="">', $this->object->reset('[img]../images/image.PNG[/img]')->parse()); // no protocol, relative png

        // security
        $this->assertEquals('(Invalid img)', $this->object->reset('[img]http://domain.com[/img]')->parse());
        $this->assertEquals(null, $this->object->reset('[img]http://domain.com/image.gif?path=http://domain.com/image.png[/img]')->parse());

        // xhtml
        $this->object->setXhtml(true);
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="" />', $this->object->reset('[img]http://domain.com/image.gif[/img]')->parse());
    }

    /**
     * Test default attribute.
     */
    public function testDefault() {
        $this->assertEquals('<img src="http://domain.com/image.gif" width="200" height="100" alt="">', $this->object->reset('[img="200x100"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img="200"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img="x100"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" width="50%" height="10%" alt="">', $this->object->reset('[img="50%x10%"]http://domain.com/image.gif[/img]')->parse());
    }

    /**
     * Test width and height attributes.
     */
    public function testWidthHeight() {
        $this->assertEquals('<img width="666" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="666"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img width="10%" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="10%"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="10664454%"]http://domain.com/image.gif[/img]')->parse()); // too many numbers
        $this->assertEquals('<img height="1337" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="1337"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img height="77%" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="77%"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="abcdef"]http://domain.com/image.gif[/img]')->parse()); // only numbers
    }

    /**
     * Test alt attribute.
     */
    public function testAlt() {
        $this->assertEquals('<img alt="This is text!" src="http://domain.com/image.gif">', $this->object->reset('[img alt="This is text!"]http://domain.com/image.gif[/img]')->parse());
        $this->assertEquals('<img alt="Alt with characters: !@)(#^!)&amp; and 12375-439830." src="http://domain.com/image.gif">', $this->object->reset('[img alt="Alt with characters: !@)(#^!)& and 12375-439830."]http://domain.com/image.gif[/img]')->parse());
    }

    /**
     * Test that query and fragment are allowed.
     */
    public function testQueryFragment() {
        $this->assertEquals('<img src="http://domain.com/image.gif?size=600" alt="">', $this->object->reset('[img]http://domain.com/image.gif?size=600[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif#fragment" alt="">', $this->object->reset('[img]http://domain.com/image.gif#fragment[/img]')->parse());
        $this->assertEquals('<img src="http://domain.com/image.gif?size=600&amp;rating=r#fragment" alt="">', $this->object->reset('[img]http://domain.com/image.gif?size=600&rating=r#fragment[/img]')->parse());
    }

}
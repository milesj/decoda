<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\ImageFilter;
use mjohnson\decoda\tests\TestCase;

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

		// variations
		$this->assertEquals('<img src="https://domain.com/image.jpg" alt="">', $this->object->reset('[img]https://domain.com/image.jpg[/img]')->parse()); // https, jpg
		$this->assertEquals('<img src="//domain.com/image.PNG" alt="">', $this->object->reset('[img]//domain.com/image.PNG[/img]')->parse()); // no protocol, png

		// width, height
		$this->assertEquals('<img width="666" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="666"]http://domain.com/image.gif[/img]')->parse());
		$this->assertEquals('<img width="10%" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="10%"]http://domain.com/image.gif[/img]')->parse());
		$this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img width="10664454%"]http://domain.com/image.gif[/img]')->parse()); // too many numbers
		$this->assertEquals('<img height="1337" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="1337"]http://domain.com/image.gif[/img]')->parse());
		$this->assertEquals('<img height="77%" src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="77%"]http://domain.com/image.gif[/img]')->parse());
		$this->assertEquals('<img src="http://domain.com/image.gif" alt="">', $this->object->reset('[img height="abcdef"]http://domain.com/image.gif[/img]')->parse()); // only numbers

		// alt
		$this->assertEquals('<img alt="This is text!" src="http://domain.com/image.gif">', $this->object->reset('[img alt="This is text!"]http://domain.com/image.gif[/img]')->parse());
		$this->assertEquals('<img alt="Alt with characters: !@)(#^!)&amp; and 12375-439830." src="http://domain.com/image.gif">', $this->object->reset('[img alt="Alt with characters: !@)(#^!)& and 12375-439830."]http://domain.com/image.gif[/img]')->parse());

		// security
		$this->assertEquals('(Invalid img)', $this->object->reset('[img]http://domain.com[/img]')->parse());
		$this->assertEquals(null, $this->object->reset('[img]http://domain.com/image.gif?path=http://domain.com/image.png[/img]')->parse());

		// xhtml
		$this->object->setXhtml(true);
		$this->assertEquals('<img src="http://domain.com/image.gif" alt="" />', $this->object->reset('[img]http://domain.com/image.gif[/img]')->parse());
	}

}
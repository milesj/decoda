<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\ImageFilter;
use mjohnson\decoda\hooks\EmoticonHook;
use mjohnson\decoda\tests\TestCase;

class EmoticonHookTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$decoda = new Decoda();
		$decoda->addFilter(new ImageFilter());

		$this->object = new EmoticonHook();
		$this->object->setParser($decoda);
	}

	/**
	 * Test that smiley faces are converted to emoticon images.
	 */
	public function testConversion() {
		$this->assertEquals('<img src="/images/happy.png" alt="">', $this->object->beforeParse(':)'));
		$this->assertEquals('<img src="/images/sad.png" alt="">', $this->object->beforeParse(':('));
		$this->assertEquals('<img src="/images/kiss.png" alt="">', $this->object->beforeParse(':3'));
		$this->assertEquals('<img src="/images/meh.png" alt="">', $this->object->beforeParse('&lt;_&lt;'));
		$this->assertEquals('<img src="/images/heart.png" alt="">', $this->object->beforeParse(':heart:'));
		$this->assertEquals('<img src="/images/wink.png" alt="">', $this->object->beforeParse(';D'));

		// positioning
		$this->assertEquals('<img src="/images/hm.png" alt=""> at the beginning', $this->object->beforeParse(':/ at the beginning'));
		$this->assertEquals('Smiley at the end <img src="/images/gah.png" alt="">', $this->object->beforeParse('Smiley at the end :O'));
		$this->assertEquals('Smiley in the middle <img src="/images/tongue.png" alt=""> of a string', $this->object->beforeParse('Smiley in the middle :P of a string'));
	}

}
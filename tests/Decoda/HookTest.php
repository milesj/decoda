<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Test\TestCase;
use Decoda\Test\TestHook;

class HookTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new TestHook(array('key' => 'value'));
	}

	/**
	 * Test that config() returns a configuration value.
	 */
	public function testConfig() {
		$this->assertEquals('value', $this->object->config('key'));
		$this->assertEquals(null, $this->object->config('foobar'));
	}

	/**
	 * Test that setParser() sets Decoda and getParser() returns it.
	 */
	public function testGetSetParser() {
		$this->assertEquals(null, $this->object->getParser());

		$this->object->setParser(new Decoda());
		$this->assertInstanceOf('Decoda\Decoda', $this->object->getParser());
	}

	/**
	 * Test that message() returns a localized string.
	 */
	public function testMessage() {
		$this->object->setParser(new Decoda());

		$this->assertEquals('Quote by {author}', $this->object->message('quoteBy'));
	}

}
<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\hooks\CensorHook;
use mjohnson\decoda\tests\TestCase;

class CensorHookTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new CensorHook();
		$this->object->setParser(new Decoda());
	}

	/**
	 * Test that beforeParse() will convert curse words to a censored equivalent. Will also take into account mulitple characters.
	 */
	public function testParse() {
		$this->assertNotEquals('fuck', $this->object->beforeParse('fuck'));
		$this->assertNotEquals('fuuuccckkkkk', $this->object->beforeParse('fuuuccckkkkk'));
		$this->assertNotEquals('fffUUUcccKKKkk', $this->object->beforeParse('fffUUUcccKKKkk'));
		$this->assertNotEquals('Hey, fuck you buddy!', $this->object->beforeParse('Hey, fuck you buddy!'));
	}

	/**
	 * Test that blacklist() censors words on the fly.
	 */
	public function testBlacklist() {
		$this->assertEquals('word', $this->object->beforeParse('word'));

		$this->object->blacklist(array('word'));
		$this->assertNotEquals('word', $this->object->beforeParse('word'));
		$this->assertNotEquals('wooRrrDdd', $this->object->beforeParse('wooRrrDdd'));
	}

}
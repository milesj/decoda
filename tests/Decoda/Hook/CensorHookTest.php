<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Hook\CensorHook;
use Decoda\Test\TestCase;

class CensorHookTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new CensorHook();
		// This Hook don't depends from Decoda
		// $this->object->setParser(new Decoda());
		$this->object->startup();
	}

	/**
	 * Test that beforeParse() will convert curse words to a censored equivalent. Will also take into account mulitple characters.
	 */
	public function testParse() {
		$this->assertNotEquals('fuck', $this->object->beforeParse('fuck'));
		$this->assertNotEquals('fuuuccckkkkk', $this->object->beforeParse('fuuuccckkkkk'));
		$this->assertNotEquals('fffUUUcccKKKkk', $this->object->beforeParse('fffUUUcccKKKkk'));
		$this->assertNotEquals('Hey, fuck you buddy!', $this->object->beforeParse('Hey, fuck you buddy!'));

		// Don't censor words that share a blacklist
		$this->assertNotEquals('nig', $this->object->beforeParse('nig'));
		$this->assertNotEquals('nigger', $this->object->beforeParse('nigger'));
		$this->assertEquals('Night', $this->object->beforeParse('Night'));
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
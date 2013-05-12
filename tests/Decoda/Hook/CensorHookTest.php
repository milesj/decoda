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
		$this->object->setParser(new Decoda());
		$this->object->startup();
	}

	/**
	 * Test that beforeParse() will convert curse words to a censored equivalent. Will also take into account mulitple characters.
	 */
	public function testParse() {
		$this->assertRegExp('/[@#\$\*!\?%]{4}/', $this->object->beforeParse('fuck'));
		$this->assertRegExp('/[@#\$\*!\?%]{4} [@#\$\*!\?%]{4} [@#\$\*!\?%]{4}/', $this->object->beforeParse('fuck fuck fuck'));
		$this->assertRegExp('/[@#\$\*!\?%]{12}/', $this->object->beforeParse('fuuuccckkkkk'));
		$this->assertRegExp('/[@#\$\*!\?%]{14}/', $this->object->beforeParse('fffUUUcccKKKkk'));
		$this->assertRegExp('/Hey, [@#\$\*!\?%]{4} you buddy!/', $this->object->beforeParse('Hey, fuck you buddy!'));

		// Don't censor words that share a blacklist
		$this->assertRegExp('/[@#\$\*!\?%]{3}/', $this->object->beforeParse('nig'));
		$this->assertRegExp('/[@#\$\*!\?%]{6}/', $this->object->beforeParse('nigger'));
		$this->assertEquals('Night', $this->object->beforeParse('Night'));
	}

	/**
	 * Test that blacklist() censors words on the fly.
	 */
	public function testBlacklist() {
		$this->assertEquals('word', $this->object->beforeParse('word'));

		$this->object->blacklist(array('word'));
		$this->assertRegExp('/[@#\$\*!\?%]{4}/', $this->object->beforeParse('word'));
		$this->assertRegExp('/[@#\$\*!\?%]{9}/', $this->object->beforeParse('wooRrrDdd'));
	}

}
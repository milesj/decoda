<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\hooks\CodeHook;
use mjohnson\decoda\tests\TestCase;

class CodeHookTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new CodeHook();
		$this->object->setParser(new Decoda());
	}

	/**
	 * Test that beforeParse() encodes code data so that it wont be converted, then afterParse() decodes the data.
	 */
	public function testConversion() {
		$this->assertEquals('[code="php]VGVzdCBbYl1jb2RlWy9iXSE=[/code]', $this->object->beforeParse('[code="php]Test [b]code[/b]![/code]'));
		$this->assertEquals('<pre class="decoda-code php">Test [b]code[/b]!</pre>', $this->object->beforeParse('<pre class="decoda-code php">Test [b]code[/b]!</pre>'));
	}

}
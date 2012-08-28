<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\DefaultFilter;
use mjohnson\decoda\tests\TestCase;

class DefaultFilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new DefaultFilter());
	}

	/**
	 * Test [b] renders bold text.
	 */
	public function testBold() {
		$this->assertEquals('<b>Bold</b>', $this->object->reset('[b]Bold[/b]')->parse());

		$this->object->setXhtml(true);
		$this->assertEquals('<strong>Bold</strong>', $this->object->reset('[b]Bold[/b]')->parse());
	}

	/**
	 * Test [i] renders italic text.
	 */
	public function testItalic() {
		$this->assertEquals('<i>Italic</i>', $this->object->reset('[i]Italic[/i]')->parse());

		$this->object->setXhtml(true);
		$this->assertEquals('<em>Italic</em>', $this->object->reset('[i]Italic[/i]')->parse());
	}

	/**
	 * Test [u] renders underline text.
	 */
	public function testUnderline() {
		$this->assertEquals('<u>Underline</u>', $this->object->reset('[u]Underline[/u]')->parse());
	}

	/**
	 * Test [s] renders strike through text.
	 */
	public function testStrike() {
		$this->assertEquals('<del>Strike</del>', $this->object->reset('[s]Strike[/s]')->parse());
	}

	/**
	 * Test [sub] renders subscript text.
	 */
	public function testSubscript() {
		$this->assertEquals('<sub>Subscript</sub>', $this->object->reset('[sub]Subscript[/sub]')->parse());
	}

	/**
	 * Test [sup] renders superscript text.
	 */
	public function testSuperscript() {
		$this->assertEquals('<sup>Superscript</sup>', $this->object->reset('[sup]Superscript[/sup]')->parse());
	}

}
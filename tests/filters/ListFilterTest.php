<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\ListFilter;
use mjohnson\decoda\tests\TestCase;

class ListFilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new ListFilter());
	}

	/**
	 * Test that [list] renders ul lists and only accepts li children.
	 */
	public function testList() {
		$this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset('[list][/list]')->parse());

		// children
		$this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset("[list]\n\n\nOnly li's are allowed here\n\n\n[/list]")->parse());
		$this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list][li]List item[/li][/list]")->parse());
		$this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list]\n[li]List item[/li]\n\n\n[/list]")->parse());

		// whitelist
		$this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset("[list][b]Not a list item[/b][/list]")->parse());
	}

	/**
	 * Test that [olist] renders ol lists and only accepts li children.
	 */
	public function testOlist() {
		$this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset('[olist][/olist]')->parse());

		// children
		$this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset("[olist]\n\n\nOnly li's are allowed here\n\n\n[/olist]")->parse());
		$this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist][li]List item[/li][/olist]")->parse());
		$this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist]\n[li]List item[/li]\n\n\n[/olist]")->parse());

		// whitelist
		$this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset("[olist][b]Not a list item[/b][/olist]")->parse());
	}

	/**
	 * Test that [li] renders li tags and only within lists.
	 */
	public function testLi() {
		$this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list][li]List item[/li][/list]")->parse());
		$this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist][li]List item[/li][/olist]")->parse());

		// must be within list or olist
		$this->assertEquals('List item', $this->object->reset('[li]List item[/li]')->parse());
		$this->assertEquals('[b]List item[/b]', $this->object->reset('[b][li]List item[/li][/b]')->parse());
	}

}
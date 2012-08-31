<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\EmailFilter;
use mjohnson\decoda\tests\TestCase;

class EmailFilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new EmailFilter());
	}

	/**
	 * Test that [email] renders anchor links that send to email.
	 */
	public function testEmail() {
		$expected = '<a href="mailto:&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;</a>';
		$this->assertEquals($expected, $this->object->reset('[email]email@domain.com[/email]')->parse());

		$expected = '<a href="mailto:&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">Email me!</a>';
		$this->assertEquals($expected, $this->object->reset('[email="email@domain.com"]Email me![/email]')->parse());

		$expected = 'email@domain';
		$this->assertEquals($expected, $this->object->reset('[email]email@domain[/email]')->parse());

		$expected = 'Email me!';
		$this->assertEquals($expected, $this->object->reset('[email="email@domain"]Email me![/email]')->parse());
	}

}
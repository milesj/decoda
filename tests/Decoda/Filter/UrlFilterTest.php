<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\UrlFilter;
use Decoda\Test\TestCase;

class UrlFilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new UrlFilter());
	}

	/**
	 * Test that [url] renders anchor links and validates URLs.
	 */
	public function testUrl() {
		$this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->reset('[url]http://domain.com[/url]')->parse());
		$this->assertEquals('<a href="https://domain.com">https://domain.com</a>', $this->object->reset('[url]https://domain.com[/url]')->parse());
		$this->assertEquals('<a href="ftp://domain.com">ftp://domain.com</a>', $this->object->reset('[url]ftp://domain.com[/url]')->parse());
		$this->assertEquals('<a href="irc://domain.com">irc://domain.com</a>', $this->object->reset('[url]irc://domain.com[/url]')->parse());

		$this->assertEquals('<a href="http://domain.com">Link</a>', $this->object->reset('[url="http://domain.com"]Link[/url]')->parse());
		$this->assertEquals('<a href="http://domain.com?query=string">Link</a>', $this->object->reset('[url="http://domain.com?query=string"]Link[/url]')->parse());
		$this->assertEquals('<a href="https://domain.com?query=string&amp;key=value">Link</a>', $this->object->reset('[url="https://domain.com?query=string&key=value"]Link[/url]')->parse());
		$this->assertEquals('<a href="http://domain.com?query=string&amp;key=value#frag">Link</a>', $this->object->reset('[url="http://domain.com?query=string&key=value#frag"]Link[/url]')->parse());
		$this->assertEquals('<a href="ftps://user:pass@domain.com?query=string&amp;key=value">Link</a>', $this->object->reset('[url="ftps://user:pass@domain.com?query=string&key=value"]Link[/url]')->parse());
		$this->assertEquals('<a href="http://domain.com:8080?query=string&amp;key=value">Link</a>', $this->object->reset('[url="http://domain.com:8080?query=string&key=value"]Link[/url]')->parse());

		// Invalid
		$this->assertEquals('http:domain.com', $this->object->reset('[url]http:domain.com[/url]')->parse());
		$this->assertEquals('file://image.png', $this->object->reset('[url]file://image.png[/url]')->parse());

		// Test URLs with a trailing slash
		$this->assertEquals('<a href="http://domain.com/">http://domain.com/</a>', $this->object->reset('[url]http://domain.com/[/url]')->parse());
		$this->assertEquals('<a href="http://domain.com/">Test</a>', $this->object->reset('[url="http://domain.com/"]Test[/url]')->parse());

	}

	public function testUrlWhenStrictIsFalse() {
		$this->object->setStrict(false);

		// Test URLs with a trailing slash
		$this->assertEquals('<a href="http://domain.com/">Test</a>', $this->object->reset('[url=http://domain.com/]Test[/url]')->parse());
	}

}
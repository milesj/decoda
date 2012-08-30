<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\tests\TestCase;
use mjohnson\decoda\tests\TestFilter;

class FilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new TestFilter(array('key' => 'value'));
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
		$this->assertInstanceOf('mjohnson\decoda\Decoda', $this->object->getParser());
	}

	/**
	 * Test that message() returns a localized string.
	 */
	public function testMessage() {
		$this->object->setParser(new Decoda());
		$this->assertEquals('Quote by {author}', $this->object->message('quoteBy'));
	}

	/**
	 * Test that parse() renders a Decoda tag into an HTML tag.
	 */
	public function testParse() {
		$this->assertEquals('', $this->object->parse());
	}

	/**
	 * Test that tags() returns all tags.
	 */
	public function testTags() {
		$this->assertEquals(array(
			'example' => array(
				'tag' => 'example',
				'displayType' => TestFilter::TYPE_INLINE,
				'htmlAttributes' => array(
					'class' => 'example'
				)
			)
		), $this->object->tags());
	}

	/**
	 * Test that tag() returns a tag settings and the defaults.
	 */
	public function testTag() {
		$expected = array(
			'key' => 'fakeTag',
			'tag' => '',
			'template' => '',
			'displayType' => TestFilter::TYPE_BLOCK,
			'allowedTypes' => TestFilter::TYPE_BOTH,
			'attributes' => array(),
			'mapAttributes' => array(),
			'htmlAttributes' => array(),
			'escapeAttributes' => true,
			'lineBreaks' => TestFilter::NL_CONVERT,
			'autoClose' => false,
			'preserveTags' => false,
			'contentPattern' => '',
			'testNoDefault' => false,
			'parent' => array(),
			'childrenWhitelist' => array(),
			'childrenBlacklist' => array(),
			'maxChildDepth' => -1,
		);

		$this->assertEquals($expected, $this->object->tag('fakeTag'));

		$expected['key'] = 'example';
		$expected['tag'] = 'example';
		$expected['displayType'] = TestFilter::TYPE_INLINE;
		$expected['htmlAttributes'] = array('class' => 'example');

		$this->assertEquals($expected, $this->object->tag('example'));
	}

}


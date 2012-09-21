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
		$this->object->setParser(new Decoda());

		$this->assertEquals('<example>Content</example>', $this->object->parse(array(
			'tag' => 'example',
			'text' => '[example]',
			'attributes' => array(),
			'type' => Decoda::TAG_OPEN,
			'children' => array('Content')
		), 'Content'));
	}

	/**
	 * Test that tags() returns all tags.
	 */
	public function testTags() {
		$this->assertEquals(array(
			'example',
			'template',
			'templateMissing',
			'inline',
			'inlineAllowInline',
			'inlineAllowBlock',
			'inlineAllowBoth',
			'block',
			'blockAllowInline',
			'blockAllowBlock',
			'blockAllowBoth',
			'attributes',
			'parent',
			'parentNoPersist',
			'parentWhitelist',
			'parentBlacklist',
			'whiteChild',
			'blackChild',
			'depth',
			'lineBreaksRemove',
			'lineBreaksPreserve',
			'lineBreaksConvert',
			'pattern',
			'autoClose'
		), array_keys($this->object->tags()));
	}

	/**
	 * Test that tag() returns a tag settings and the defaults.
	 */
	public function testTag() {
		$expected = array(
			'tag' => 'fakeTag',
			'htmlTag' => '',
			'template' => '',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'attributes' => array(),
			'mapAttributes' => array(),
			'htmlAttributes' => array(),
			'escapeAttributes' => true,
			'lineBreaks' => Decoda::NL_CONVERT,
			'autoClose' => false,
			'preserveTags' => false,
			'contentPattern' => '',
			'parent' => array(),
			'childrenWhitelist' => array(),
			'childrenBlacklist' => array(),
			'maxChildDepth' => -1,
			'persistContent' => true
		);

		$this->assertEquals($expected, $this->object->tag('fakeTag'));

		$expected['tag'] = 'example';
		$expected['htmlTag'] = 'example';

		$this->assertEquals($expected, $this->object->tag('example'));
	}

}


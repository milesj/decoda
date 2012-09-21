<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\CodeFilter;
use mjohnson\decoda\tests\TestCase;

class CodeFilterTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new CodeFilter());
	}

	/**
	 * Test that [code] renders pre-formatted code blocks and doesn't render Decoda tags.
	 */
	public function testCode() {
		$expected = '<pre class="decoda-code">$variable = "Code Block";</pre>';
		$this->assertEquals($expected, $this->object->reset('[code]$variable = "Code Block";[/code]')->parse());

		$expected = '<pre class="decoda-code php">$variable = "Code Block";</pre>';
		$this->assertEquals($expected, $this->object->reset('[code="php"]$variable = "Code Block";[/code]')->parse());

		$expected = '<pre class="decoda-code" data-highlight="10,20">$variable = "Code Block";</pre>';
		$this->assertEquals($expected, $this->object->reset('[code hl="10,20"]$variable = "Code Block";[/code]')->parse());

		$expected = '<pre class="decoda-code php" data-highlight="10,20">$variable = "Code Block";</pre>';
		$this->assertEquals($expected, $this->object->reset('[code="php" hl="10,20"]$variable = "Code Block";[/code]')->parse());

		$expected = '<pre class="decoda-code">Code block [b]with Decoda[/b] tags.</pre>';
		$this->assertEquals($expected, $this->object->reset('[code]Code block [b]with Decoda[/b] tags.[/code]')->parse());

		$expected = '<pre class="decoda-code">Code block &lt;strong&gt;with HTML&lt;/strong&gt; tags.</pre>';
		$this->assertEquals($expected, $this->object->reset('[code]Code block <strong>with HTML</strong> tags.[/code]')->parse());

		$string = <<<'CODE'
[code hl="1,15"]<?php
abstract class FilterAbstract implements Filter {

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		$defaults = $this->_defaults;
		$defaults[\'key\'] = $tag;

		if (isset($this->_tags[$tag])) {
			return $this->_tags[$tag] + $defaults;
		}

		return $defaults;
	}

} ?>[/code]
CODE;

		$expected = <<<'CODE'
<pre class="decoda-code" data-highlight="1,15">&lt;?php
abstract class FilterAbstract implements Filter {

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		$defaults = $this-&gt;_defaults;
		$defaults[\'key\'] = $tag;

		if (isset($this-&gt;_tags[$tag])) {
			return $this-&gt;_tags[$tag] + $defaults;
		}

		return $defaults;
	}

} ?&gt;</pre>
CODE;

		$this->assertEquals($expected, $this->object->reset($string)->parse());
	}

	/**
	 * Test [var] renders a variable tag.
	 */
	public function testVar() {
		$this->assertEquals('<code>Variable</code>', $this->object->reset('[var]Variable[/var]')->parse());
	}

}
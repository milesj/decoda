<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests;

class DecodaTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new TestFilter());
	}

	/**
	 * Test that nesting of inline and block elements.
	 */
	public function testDisplayAndAllowedTypes() {
		// Inline with inline children
		$string = '[inlineAllowInline][inline]Inline[/inline][block]Block[/block][/inlineAllowInline]';
		$this->assertEquals('<inlineAllowInline><inline>Inline</inline>Block</inlineAllowInline>', $this->object->reset($string)->parse());

		// Inline with block children (block are never allowed)
		$string = '[inlineAllowBlock][inline]Inline[/inline][block]Block[/block][/inlineAllowBlock]';
		$this->assertEquals('<inlineAllowBlock>InlineBlock</inlineAllowBlock>', $this->object->reset($string)->parse());

		// Inline with both children (block are never allowed)
		$string = '[inlineAllowBoth][inline]Inline[/inline][block]Block[/block][/inlineAllowBoth]';
		$this->assertEquals('<inlineAllowBoth><inline>Inline</inline>Block</inlineAllowBoth>', $this->object->reset($string)->parse());

		// Block with inline children
		$string = '[blockAllowInline][inline]Inline[/inline][block]Block[/block][/blockAllowInline]';
		$this->assertEquals('<blockAllowInline><inline>Inline</inline>Block</blockAllowInline>', $this->object->reset($string)->parse());

		// Block with block children (inline are allowed always)
		$string = '[blockAllowBlock][inline]Inline[/inline][block]Block[/block][/blockAllowBlock]';
		$this->assertEquals('<blockAllowBlock>Inline<block>Block</block></blockAllowBlock>', $this->object->reset($string)->parse());

		// Block with both children
		$string = '[blockAllowBoth][inline]Inline[/inline][block]Block[/block][/blockAllowBoth]';
		$this->assertEquals('<blockAllowBoth><inline>Inline</inline><block>Block</block></blockAllowBoth>', $this->object->reset($string)->parse());
	}

	/**
	 * Test attribute parsing, mapping and escaping.
	 */
	public function testAttributeParsing() {
		// No attributes, has custom HTML attributes
		$string = '[attributes]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html">Attributes</attributes>', $this->object->reset($string)->parse());

		// Default attribute, uses wildcard pattern, is mapped and renamed to wildcard
		$string = '[attributes="1337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" wildcard="1337">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes="Decoda"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" wildcard="Decoda">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes="02/26/1988!"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" wildcard="02/26/1988!">Attributes</attributes>', $this->object->reset($string)->parse());

		// Alpha attribute, uses alpha pattern
		$string = '[attributes alpha="1337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes alpha="Decoda Parser"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" alpha="Decoda Parser">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes alpha="Spaces Dashes- Underscores_"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" alpha="Spaces Dashes- Underscores_">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes alpha="Other! Not* Allowed&"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html">Attributes</attributes>', $this->object->reset($string)->parse());

		// Alnum attribute, uses alpha and numeric pattern
		$string = '[attributes alnum="1337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" alnum="1337">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes alnum="Decoda Parser"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" alnum="Decoda Parser">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes alnum="Spaces Dashes- Underscores_"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" alnum="Spaces Dashes- Underscores_">Attributes</attributes>', $this->object->reset($string)->parse());

		// Numeric attribute, uses numeric pattern
		$string = '[attributes numeric="1337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" numeric="1337">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes numeric="+1,337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" numeric="+1,337">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes numeric="1,337.00"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" numeric="1,337.00">Attributes</attributes>', $this->object->reset($string)->parse());

		$string = '[attributes numeric="Decoda"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html">Attributes</attributes>', $this->object->reset($string)->parse());

		// All attributes and escaping
		$string = '[attributes="Decoda & Escaping" alpha="Decoda" alnum="Version 1.2.3" numeric="1337"]Attributes[/attributes]';
		$this->assertEquals('<attributes id="custom-html" wildcard="Decoda &amp; Escaping" alpha="Decoda" alnum="Version 1.2.3" numeric="1337">Attributes</attributes>', $this->object->reset($string)->parse());
	}

}
<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests;

use mjohnson\decoda\filters\DefaultFilter;
use mjohnson\decoda\filters\EmailFilter;
use mjohnson\decoda\filters\UrlFilter;
use \Exception;

class DecodaTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object->addFilter(new TestFilter());
	}

	/**
	 * Test that adding, getting and resetting filters work.
	 */
	public function testFilters() {
		$this->object->resetFilters();

		// Empty
		$this->assertTrue(count($this->object->getFilters()) == 1);

		try {
			$this->object->getFilter('Test');
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		try {
			$this->object->getFilterByTag('example');
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		$this->object->addFilter(new TestFilter());

		// Empty, Test
		$this->assertTrue(count($this->object->getFilters()) == 2);

		$this->assertInstanceOf('\mjohnson\decoda\filters\Filter', $this->object->getFilter('Test'));
		$this->assertInstanceOf('\mjohnson\decoda\filters\Filter', $this->object->getFilterByTag('example'));

		$this->object->resetFilters();

		// Empty
		$this->assertTrue(count($this->object->getFilters()) == 1);
	}

	/**
	 * Test that adding, getting and resetting hooks work.
	 */
	public function testHooks() {
		// Empty
		$this->assertTrue(count($this->object->getHooks()) == 1);

		try {
			$this->object->getHook('Test');
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}

		$this->object->addHook(new TestHook());

		// Empty, Censor
		$this->assertTrue(count($this->object->getHooks()) == 2);

		$this->assertInstanceOf('\mjohnson\decoda\hooks\Hook', $this->object->getHook('Test'));

		$this->object->resetHooks();

		// Empty
		$this->assertTrue(count($this->object->getHooks()) == 1);
	}

	/**
	 * Test that getting and setting an engine works.
	 */
	public function testEngines() {
		$this->assertInstanceOf('\mjohnson\decoda\engines\PhpEngine', $this->object->getEngine());

		$this->object->setEngine(new TestEngine());

		$this->assertInstanceOf('\mjohnson\decoda\tests\TestEngine', $this->object->getEngine());
	}

	/**
	 * Test that disable() stops all tag parsing.
	 */
	public function testDisable() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('<b>Bold</b> <i>Italics</i>', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());

		$this->object->disable(true);

		$this->assertEquals('Bold Italics', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());
	}

	/**
	 * Test that message() returns a formatted string.
	 */
	public function testMessage() {
		$this->assertEquals('', $this->object->message('foobar'));
		$this->assertEquals('Spoiler', $this->object->message('spoiler'));
		$this->assertEquals('Quote by Miles', $this->object->message('quoteBy', array('author' => 'Miles')));
	}

	/**
	 * Test that setBrackets() changes the tag brackets.
	 */
	public function testBrackets() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('{b}Bold{/b}', $this->object->reset('{b}Bold{/b}')->parse());

		$this->object->setBrackets('{', '}');

		$this->assertEquals('<b>Bold</b>', $this->object->reset('{b}Bold{/b}')->parse());

		try {
			$this->object->setBrackets('', null);
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that setEscaping() toggles HTML escaping.
	 */
	public function testEscaping() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('&lt;b&gt;Bold&lt;/b&gt; <i>Italics</i>', $this->object->reset('<b>Bold</b> [i]Italics[/i]')->parse());

		$this->object->setEscaping(false);

		$this->assertEquals('<b>Bold</b> <i>Italics</i>', $this->object->reset('<b>Bold</b> [i]Italics[/i]')->parse());
	}

	/**
	 * Test that setLocale() changes the locale for messages.
	 */
	public function testLocale() {
		$this->assertEquals('Spoiler', $this->object->message('spoiler'));

		$this->object->setLocale('es-mx');

		$this->assertEquals('Alerón', $this->object->message('spoiler'));

		try {
			$this->object->setLocale('no-no');
			$this->assertTrue(false);
		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that resetting or removing filters doesn't convert tags.
	 */
	public function testReset() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('<b>Bold</b> <i>Italics</i>', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());

		$this->object->resetFilters();

		$this->assertEquals('[b]Bold[/b] [i]Italics[/i]', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());
	}

	/**
	 * Test that setShorthand() applies short hand variations for URL and email.
	 */
	public function testShorthand() {
		$this->object->addFilter(new UrlFilter());
		$this->object->addFilter(new EmailFilter(array('encrypt' => false)));

		$this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->reset('[url]http://domain.com[/url]')->parse());
		$this->assertEquals('<a href="mailto:user@domain.com">user@domain.com</a>', $this->object->reset('[email]user@domain.com[/email]')->parse());

		$this->object->setShorthand(true);

		$this->assertEquals('[<a href="http://domain.com">link</a>]', $this->object->reset('[url]http://domain.com[/url]')->parse());
		$this->assertEquals('[<a href="mailto:user@domain.com">mail</a>]', $this->object->reset('[email]user@domain.com[/email]')->parse());
	}

	/**
	 * Test that setStrict() toggles strict attribute parsing.
	 */
	public function testStrict() {
		// Strict requires double quotes
		$this->assertEquals('<attributes id="custom-html" numeric="123" alpha="abc">Content</attributes>', $this->object->reset('[attributes numeric="123" alpha="abc"]Content[/attributes]')->parse());
		$this->assertEquals('<attributes id="custom-html">Content</attributes>', $this->object->reset('[attributes numeric=123 alpha=abc]Content[/attributes]')->parse());

		// Disabling strict doesn't require quotes
		$this->object->setStrict(false);

		$this->assertEquals('<attributes id="custom-html" numeric="123" alpha="abc">Content</attributes>', $this->object->reset('[attributes numeric="123" alpha="abc"]Content[/attributes]')->parse());
		$this->assertEquals('<attributes id="custom-html" numeric="123" alpha="abc">Content</attributes>', $this->object->reset('[attributes numeric=123 alpha=abc]Content[/attributes]')->parse());

		// Now lets mix the two
		$this->assertEquals('<attributes id="custom-html" alpha="abc" numeric="123">Content</attributes>', $this->object->reset('[attributes numeric=123 alpha="abc"]Content[/attributes]')->parse());

		// Now with spaces and mixed values
		$this->assertEquals('<attributes id="custom-html" wildcard="Something" alnum="abc">Content</attributes>', $this->object->reset('[attributes=Something "quotes" here alnum=abc 123]Content[/attributes]')->parse());
		$this->assertEquals('<attributes id="custom-html" wildcard="Miles&quot;gearvOsh&quot;Johnson" alnum="abc-123">Content</attributes>', $this->object->reset('[attributes=Miles"gearvOsh"Johnson alnum=abc-123]Content[/attributes]')->parse());
	}

	/**
	 * Test that setXhtml() changes all tags to XHTML equivalents.
	 */
	public function testXhtml() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals("<b>Bold</b><br>\n<i>Italics</i>", $this->object->reset("[b]Bold[/b]\n[i]Italics[/i]")->parse());

		$this->object->setXhtml(true);

		$this->assertEquals("<strong>Bold</strong><br />\n<em>Italics</em>", $this->object->reset("[b]Bold[/b]\n[i]Italics[/i]")->parse());
	}

	/**
	 * Test that blacklist() denies specific tags.
	 */
	public function testBlacklist() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('<b>Bold</b> <i>Italics</i>', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());

		$this->assertEquals('<b>Bold</b> Italics', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->blacklist('i')->parse());
	}

	/**
	 * Test that whitelist() allows specific tags.
	 */
	public function testWhitelist() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('<b>Bold</b> <i>Italics</i>', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->parse());

		$this->assertEquals('<b>Bold</b> Italics', $this->object->reset('[b]Bold[/b] [i]Italics[/i]')->whitelist('b')->parse());
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

	/**
	 * Test parent and child nesting hierarchy.
	 */
	public function testParentChildNesting() {
		// Whitelist will only allow white children
		$string = '[parentWhitelist][whiteChild]White[/whiteChild][blackChild]Black[/blackChild][/parentWhitelist]';
		$this->assertEquals('<parentWhitelist><whiteChild>White</whiteChild></parentWhitelist>', $this->object->reset($string)->parse());

		// Blacklist will not allow white children
		$string = '[parentBlacklist][whiteChild]White[/whiteChild][blackChild]Black[/blackChild][/parentBlacklist]';
		$this->assertEquals('<parentBlacklist><blackChild>Black</blackChild></parentBlacklist>', $this->object->reset($string)->parse());

		// No whitelist or blacklist
		$string = '[parent][whiteChild]White[/whiteChild][blackChild]Black[/blackChild][/parent]';
		$this->assertEquals('<parent><whiteChild>White</whiteChild><blackChild>Black</blackChild></parent>', $this->object->reset($string)->parse());

		// Children can only be nested in a parent
		$string = '[example][whiteChild]White[/whiteChild][blackChild]Black[/blackChild][/example]';
		$this->assertEquals('<example>WhiteBlack</example>', $this->object->reset($string)->parse());

		$string = '[whiteChild]White[/whiteChild][blackChild]Black[/blackChild]';
		$this->assertEquals('WhiteBlack', $this->object->reset($string)->parse());

		// Children can only be nested in a parent -- but do not persist the content
		$string = '[parentNoPersist][whiteChild]White[/whiteChild][blackChild]Black[/blackChild][/parentNoPersist]';
		$this->assertEquals('<parentNoPersist></parentNoPersist>', $this->object->reset($string)->parse());
	}

	/**
	 * Test max nesting depth.
	 */
	public function testMaxNestingDepth() {
		// No nested
		$string = '[depth]1[/depth]';
		$this->assertEquals('<depth>1</depth>', $this->object->reset($string)->parse());

		// 1 nested
		$string = '[depth]1 [depth]2[/depth][/depth]';
		$this->assertEquals('<depth>1 <depth>2</depth></depth>', $this->object->reset($string)->parse());

		// 2 nested
		$string = '[depth]1 [depth]2 [depth]3[/depth][/depth][/depth]';
		$this->assertEquals('<depth>1 <depth>2 <depth>3</depth></depth></depth>', $this->object->reset($string)->parse());

		// 3 nested - over the max so remove
		$string = '[depth]1 [depth]2 [depth]3 [depth]4[/depth][/depth][/depth][/depth]';
		$this->assertEquals('<depth>1 <depth>2 <depth>3 </depth></depth></depth>', $this->object->reset($string)->parse());
	}

	/**
	 * Test CRLF formatting.
	 */
	public function testNewlineFormatting() {
		// Remove CRLF
		$string = "[lineBreaksRemove]Line\nBreak\rTests[/lineBreaksRemove]";
		$this->assertEquals("<lineBreaksRemove>LineBreakTests</lineBreaksRemove>", $this->object->reset($string)->parse());

		// Preserve CRLF
		$string = "[lineBreaksPreserve]Line\nBreak\rTests[/lineBreaksPreserve]";
		$this->assertEquals("<lineBreaksPreserve>Line\nBreak\rTests</lineBreaksPreserve>", $this->object->reset($string)->parse());

		// Convert CRLF to <br>
		$string = "[lineBreaksConvert]Line\nBreak\rTests[/lineBreaksConvert]";
		$this->assertEquals("<lineBreaksConvert>Line<br>Break<br>Tests</lineBreaksConvert>", $this->object->reset($string)->parse());

		// Test nested
		$string = "[lineBreaksRemove]Line\nBreak\rTests[lineBreaksConvert]Line\nBreak\rTests[/lineBreaksConvert][/lineBreaksRemove]";
		$this->assertEquals("<lineBreaksRemove>LineBreakTests<lineBreaksConvert>Line<br>Break<br>Tests</lineBreaksConvert></lineBreaksRemove>", $this->object->reset($string)->parse());
	}

	/**
	 * Test that the content of the tag passes a regex pattern.
	 */
	public function testContentPatternMatching() {
		// Shouldn't pass
		$string = '[pattern]userpass[/pattern]';
		$this->assertEquals('(Invalid pattern)', $this->object->reset($string)->parse());

		// Should pass
		$string = '[pattern]user@pass[/pattern]';
		$this->assertEquals('<pattern>user@pass</pattern>', $this->object->reset($string)->parse());

		// Should pass with attributes
		$string = '[pattern="test"]user@pass[/pattern]';
		$this->assertEquals('<pattern attr="test">user@pass</pattern>', $this->object->reset($string)->parse());
	}

	/**
	 * Test that the content of the tag passes a regex pattern.
	 */
	public function testAutoClosingTags() {
		$this->object->setXhtml(true);

		// No content or attributes
		$string = '[autoClose]Content[/autoClose]';
		$this->assertEquals('<autoClose />', $this->object->reset($string)->parse());

		// No content with attributes
		$string = '[autoClose foo="1" bar="2"]Content[/autoClose]';
		$this->assertEquals('<autoClose foo="1" bar="2" />', $this->object->reset($string)->parse());
	}

	/**
	 * Test for unclosed tags.
	 */
	public function testUnclosedTags() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('Bold', $this->object->reset('[b]Bold')->parse());
		$this->assertEquals('Italics', $this->object->reset('Italics[/i]')->parse());
		$this->assertEquals('Bold <i>Italics</i>', $this->object->reset('[b]Bold [i]Italics[/i]')->parse());
		$this->assertEquals('<b>Bold <i>Italics</i> Underline</b>', $this->object->reset('[b]Bold [i]Italics[/i] [u]Underline[/b]')->parse());
	}

	/**
	 * Test for invalid nested tags.
	 */
	public function testInvalidNesting() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('<b>Bold Italics</b>', $this->object->reset('[b]Bold [i]Italics[/b][/i]')->parse());
		$this->assertEquals('<b>Bold <i>Italics</i> Underline</b>', $this->object->reset('[b]Bold [i]Italics[/i] [u]Underline[/b][/u]')->parse());
		$this->assertEquals('<b>Bold Italics Underline</b>', $this->object->reset('[b]Bold [i]Italics [u]Underline[/b][/i][/u]')->parse());
		$this->assertEquals('<b>Bold Italics <u>Underline</u></b>', $this->object->reset('[b]Bold [i]Italics [u]Underline[/u][/b][/i]')->parse());
	}

	/**
	 * Test that self closing tags work.
	 */
	public function testSelfClosingTags() {
		$this->object->addFilter(new DefaultFilter());

		$this->assertEquals('Text <br> and <hr>', $this->object->reset('Text [br/] and [hr/]')->parse());
		$this->assertEquals('Text <br> and <hr>', $this->object->reset('Text [br /] and [hr /]')->parse());
		$this->assertEquals('<br><br>', $this->object->reset('[br/][br][br /]')->parse());
		$this->assertEquals('<br>', $this->object->reset('[br]Content[/br]')->parse());

		$this->object->setXhtml(true);

		$this->assertEquals('Text <br /> and <hr />', $this->object->reset('Text [br/] and [hr/]')->parse());
		$this->assertEquals('Text <br /> and <hr />', $this->object->reset('Text [br /] and [hr /]')->parse());
		$this->assertEquals('<br /><br />', $this->object->reset('[br/][br][br /]')->parse());
		$this->assertEquals('<br />', $this->object->reset('[br]Content[/br]')->parse());
	}

}
<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Filter\CodeFilter;
use Decoda\Hook\CodeHook;
use Decoda\Test\TestCase;

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
        $this->assertEquals('[code="php]$$CODE0$$[/code]', $this->object->beforeParse('[code="php]Test [b]code[/b]![/code]'));
        $this->assertEquals('<pre class="decoda-code php">Test [b]code[/b]!</pre>', $this->object->beforeParse('<pre class="decoda-code php">Test [b]code[/b]!</pre>'));

        // Test nested bbcode code tag
        $this->assertEquals('[code]$$CODE0$$[/code]', $this->object->beforeParse('[code][color=#ff0000]bbcode color[/color][/code]'));

        // Test nested bbcode surround by text
        $this->assertEquals('[code="bbcode"]$$CODE0$$[/code]', $this->object->beforeParse('[code="bbcode"]example of code : [code]bliblablou[/code] some other code[/code]'));

        // Test parsing multipe code tags separated by text
        $this->assertEquals('[code]$$CODE0$$[/code]AB[code]$$CODE1$$[/code]', $this->object->beforeParse('[code]bliblablou[/code]AB[code]foobar[/code]'));

        // Test parsing dummy nested bbcode into code tag, it will not convert dummy code tag as it does not respect recursivity
        $this->assertEquals('[code]$$CODE0$$[/code]', $this->object->beforeParse('[code][code]bliblablou[/code]AB[code][code]foobar[/code][/code]'));
    }

    /**
     * Test that code blocks are cached between events.
     */
    public function testBeforeAndAfter() {
        $string = '[code="php"]Block 1[/code] Something [code]Block 2[/code] And something [code hl="1"]Block 3[/code].';

        $this->assertEquals('[code="php"]$$CODE0$$[/code] Something [code]$$CODE1$$[/code] And something [code hl="1"]$$CODE2$$[/code].', $this->object->beforeParse($string));

        $decoda = new Decoda($string);
        $decoda->addFilter(new CodeFilter());

        $this->assertEquals('<pre class="decoda-code lang-php"><code>Block 1</code></pre> Something <pre class="decoda-code"><code>Block 2</code></pre> And something <pre class="decoda-code" data-line="1"><code>Block 3</code></pre>.', $decoda->parse());
    }

}

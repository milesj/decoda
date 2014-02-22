<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\CodeFilter;
use Decoda\Test\TestCase;

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
        $expected = '<pre class="decoda-code"><code>$variable = "Code Block";</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code]$variable = "Code Block";[/code]')->parse());

        $expected = '<pre class="decoda-code lang-php"><code>$variable = "Code Block";</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code="php"]$variable = "Code Block";[/code]')->parse());

        $expected = '<pre class="decoda-code" data-line="10,20"><code>$variable = "Code Block";</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code hl="10,20"]$variable = "Code Block";[/code]')->parse());

        $expected = '<pre class="decoda-code lang-php" data-line="10,20"><code>$variable = "Code Block";</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code="php" hl="10,20"]$variable = "Code Block";[/code]')->parse());

        $expected = '<pre class="decoda-code"><code>Code block [b]with Decoda[/b] tags.</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code]Code block [b]with Decoda[/b] tags.[/code]')->parse());

        $expected = '<pre class="decoda-code"><code>Code block &lt;strong&gt;with HTML&lt;/strong&gt; tags.</code></pre>';
        $this->assertEquals($expected, $this->object->reset('[code]Code block <strong>with HTML</strong> tags.[/code]')->parse());

        $string = <<<'CODE'
[code hl="1,15"]<?php
abstract class FilterAbstract implements Filter {

    /**
     * Return a tag if it exists, and merge with defaults.
     *
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
<pre class="decoda-code" data-line="1,15"><code>&lt;?php
abstract class FilterAbstract implements Filter {

    /**
     * Return a tag if it exists, and merge with defaults.
     *
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

} ?&gt;</code></pre>
CODE;

        $this->assertEquals($this->nl($expected), $this->object->reset($string)->parse());
    }

    /**
     * Test [source] renders a code tag.
     */
    public function testSource() {
        $this->assertEquals('<code>Source</code>', $this->object->reset('[source]Source[/source]')->parse());
    }

    /**
     * Test [var] renders a variable tag.
     */
    public function testVar() {
        $this->assertEquals('<var>Variable</var>', $this->object->reset('[var]Variable[/var]')->parse());
    }

    /**
     * Test code blocks within quote blocks.
     */
    public function testCodeInQuote() {
        $this->object->addFilter(new QuoteFilter());

$string = <<<'CODE'
[quote][code="php"]doSomething();[/code][/quote]

[code="php"]doSomethingElse();[/code]
CODE;

        $expected = <<<'CODE'
<blockquote class="decoda-quote">        <div class="decoda-quote-body">        <pre class="decoda-code lang-php"><code>doSomething();</code></pre>    </div></blockquote><br><br><pre class="decoda-code lang-php"><code>doSomethingElse();</code></pre>
CODE;

        $this->assertEquals($this->nl($expected), $this->object->reset($string)->parse());
    }

}
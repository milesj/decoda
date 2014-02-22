<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\QuoteFilter;
use Decoda\Test\TestCase;

class QuoteFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new QuoteFilter());
    }

    /**
     * Test that [quote] renders quote markup and includes author or date.
     */
    public function testQuote() {
        $source = $this->clean($this->object->reset('[quote]Quote[/quote]')->parse());
        $this->assertEquals('<blockquote class="decoda-quote"><div class="decoda-quote-body">Quote</div></blockquote>', $source);

        $source = $this->clean($this->object->reset('[quote date="1988-02-26 16:23:00"]Quote[/quote]')->parse());
        $this->assertEquals('<blockquote class="decoda-quote"><div class="decoda-quote-head"><span class="decoda-quote-date">Feb 26th 1988, 16:23:00</span><span class="clear"></span></div><div class="decoda-quote-body">Quote</div></blockquote>', $source);

        $source = $this->clean($this->object->reset('[quote="Miles Johnson" date="1988-02-26 16:23:00"]Quote[/quote]')->parse());
        $this->assertEquals('<blockquote class="decoda-quote"><div class="decoda-quote-head"><span class="decoda-quote-date">Feb 26th 1988, 16:23:00</span><span class="decoda-quote-author">Quote by Miles Johnson</span><span class="clear"></span></div><div class="decoda-quote-body">Quote</div></blockquote>', $source);
    }

    /**
     * Test that deep nested quotes are removed.
     */
    public function testQuoteDepth() {
        // 2 nested quotes
        $source = $this->clean($this->object->reset('[quote]#1[quote]#2[/quote][/quote]')->parse());
        $expected = '<blockquote class="decoda-quote"><div class="decoda-quote-body">#1<blockquote class="decoda-quote"><div class="decoda-quote-body">#2</div></blockquote></div></blockquote>';

        $this->assertEquals($expected, $source);

        // 3 nested quotes
        $source = $this->clean($this->object->reset('[quote]#1[quote]#2[quote]#3[/quote][/quote][/quote]')->parse());
        $expected = '<blockquote class="decoda-quote"><div class="decoda-quote-body">#1<blockquote class="decoda-quote"><div class="decoda-quote-body">#2<blockquote class="decoda-quote"><div class="decoda-quote-body">#3</div></blockquote></div></blockquote></div></blockquote>';

        $this->assertEquals($expected, $source);

        // 5 nested quotes (will restrict it to 3 since that is the max depth)
        $source = $this->clean($this->object->reset('[quote]#1[quote]#2[quote]#3[quote]#4[quote]#5[/quote][/quote][/quote][/quote][/quote]')->parse());
        $expected = '<blockquote class="decoda-quote"><div class="decoda-quote-body">#1<blockquote class="decoda-quote"><div class="decoda-quote-body">#2<blockquote class="decoda-quote"><div class="decoda-quote-body">#3</div></blockquote></div></blockquote></div></blockquote>';

        $this->assertEquals($expected, $source);
    }

}
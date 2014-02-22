<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\DefaultFilter;
use Decoda\Test\TestCase;

class DefaultFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new DefaultFilter());
    }

    /**
     * Test [b] renders bold text.
     */
    public function testBold() {
        $this->assertEquals('<b>Bold</b>', $this->object->reset('[b]Bold[/b]')->parse());

        $this->object->setXhtml(true);
        $this->assertEquals('<strong>Bold</strong>', $this->object->reset('[b]Bold[/b]')->parse());
    }

    /**
     * Test [i] renders italic text.
     */
    public function testItalic() {
        $this->assertEquals('<i>Italic</i>', $this->object->reset('[i]Italic[/i]')->parse());

        $this->object->setXhtml(true);
        $this->assertEquals('<em>Italic</em>', $this->object->reset('[i]Italic[/i]')->parse());
    }

    /**
     * Test [u] renders underline text.
     */
    public function testUnderline() {
        $this->assertEquals('<u>Underline</u>', $this->object->reset('[u]Underline[/u]')->parse());
    }

    /**
     * Test [s] renders strike through text.
     */
    public function testStrike() {
        $this->assertEquals('<del>Strike</del>', $this->object->reset('[s]Strike[/s]')->parse());
    }

    /**
     * Test [sub] renders subscript text.
     */
    public function testSubscript() {
        $this->assertEquals('<sub>Subscript</sub>', $this->object->reset('[sub]Subscript[/sub]')->parse());
    }

    /**
     * Test [sup] renders superscript text.
     */
    public function testSuperscript() {
        $this->assertEquals('<sup>Superscript</sup>', $this->object->reset('[sup]Superscript[/sup]')->parse());
    }

    /**
     * Test [abbr] renders abbreviated text.
     */
    public function testAbbr() {
        $this->assertEquals('<abbr title="National Aeronautics and Space Administration">NASA</abbr>', $this->object->reset('[abbr="National Aeronautics and Space Administration"]NASA[/abbr]')->parse());
    }

    /**
     * Test [br] renders line breaks.
     */
    public function testBr() {
        $this->assertEquals('<br>', $this->object->reset('[br /]')->parse());
        $this->assertEquals('<br>', $this->object->reset('[br/]')->parse());

        $this->object->setXhtml(true);
        $this->assertEquals('<br />', $this->object->reset('[br  /]')->parse());
        $this->assertEquals('<br />', $this->object->reset('[br   /]')->parse());
    }

    /**
     * Test [hr] renders horizontal breaks.
     */
    public function testHr() {
        $this->assertEquals('<hr>', $this->object->reset('[hr /]')->parse());
        $this->assertEquals('<hr>', $this->object->reset('[hr/]')->parse());

        $this->object->setXhtml(true);
        $this->assertEquals('<hr />', $this->object->reset('[hr  /]')->parse());
        $this->assertEquals('<hr />', $this->object->reset('[hr   /]')->parse());
    }

    /**
     * Test [time] renders time tags following a format.
     */
    public function testTime() {
        $oldTZ = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $this->assertEquals('<time datetime="1988-02-26T20:34:13+0000">Fri, Feb 26th 1988, 20:34</time>', $this->object->reset('[time]2/26/1988 20:34:13[/time]')->parse());
        date_default_timezone_set($oldTZ);
    }

}
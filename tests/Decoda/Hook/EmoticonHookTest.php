<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Filter\DefaultFilter;
use Decoda\Filter\ImageFilter;
use Decoda\Hook\EmoticonHook;
use Decoda\Test\TestCase;
use Decoda\Loader\DataLoader;
use Decoda\Test\Hook\EmoticonHookBC;

class EmoticonHookTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $decoda = new Decoda();
        $decoda->addFilter(new DefaultFilter());
        $decoda->addFilter(new ImageFilter());

        $openTag = $decoda->getConfig('open');
        $closeTag = $decoda->getConfig('close');

        $this->object = new EmoticonHook();
        $this->object->setParser($decoda);
        $this->object->addLoader(new DataLoader(array(
            'test/tag/open' => array($openTag),
            'test/tag/close' => array($closeTag),
        )));
        $this->object->startup();
    }

    /**
     * Test smiley detection according to the positioning.
     *
     * @dataProvider getSmileyDetectionData
     */
    public function testSmileyDetection($value, $expected) {
        $this->assertEquals($expected, $this->object->beforeParse($value));
    }

    /**
     * Provide all smiley patterns.
     *
     * @return array
     */
    public function getSmileyDetectionData() {
        $decoda = new Decoda();
        $openBracket = $decoda->getConfig('open');
        $closeBracket = $decoda->getConfig('close');

        return array(
            array(':/ at the beginning', '<img src="/images/hm.png" alt=""> at the beginning'),
            array('Smiley at the end :O', 'Smiley at the end <img src="/images/gah.png" alt="">'),
            array('Smiley in the middle :P of a string', 'Smiley in the middle <img src="/images/tongue.png" alt=""> of a string'),
            array(':):):)', ':):):)'),
            array('At the :)start of the word', 'At the :)start of the word'),
            array('At the mid:)dle of the word', 'At the mid:)dle of the word'),
            array('At the end:) of the word', 'At the end:) of the word'),
            array('http://', 'http://'),
            array("With a :/\n linefeed", 'With a <img src="/images/hm.png" alt="">' . "\n" . ' linefeed'),
            array("With a :/\r carriage return", 'With a <img src="/images/hm.png" alt="">' . "\r" . ' carriage return'),
            array("With a :/\t tab", 'With a <img src="/images/hm.png" alt="">' . "\t" . ' tab'),
            array(':/ :/', '<img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt="">'),
            array(' :/ :/ ', ' <img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt=""> '),
            array(' :/ :/ :/ ', ' <img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt=""> <img src="/images/hm.png" alt=""> '),

            // With a tag glue to the left of a smiley
            array(sprintf('%s', $closeBracket), '<img src="/images/test/tag/close.png" alt="">'),
            array(sprintf('foo%s:/', $closeBracket), sprintf('foo%s:/', $closeBracket)),
            array(sprintf('%s  bar  %s:/', $openBracket, $closeBracket), sprintf('%s  bar  %s<img src="/images/hm.png" alt="">', $openBracket, $closeBracket)),

            // With a tag glue to the right of a smiley
            array(sprintf('%s', $openBracket), '<img src="/images/test/tag/open.png" alt="">'),
            array(sprintf(':/%sfoo', $openBracket), sprintf(':/%sfoo', $openBracket)),
            array(sprintf(':/%s  bar  %s', $openBracket, $closeBracket), sprintf('<img src="/images/hm.png" alt="">%s  bar  %s', $openBracket, $closeBracket)),

            // With a tag glue to the left and right of a smiley
            array(sprintf('foo%s:/%sbar', $closeBracket, $openBracket), sprintf('foo%s:/%sbar', $closeBracket, $openBracket)),
            array(sprintf('%sfoo%s:/%sbar', $openBracket, $closeBracket, $openBracket), sprintf('%sfoo%s:/%sbar', $openBracket, $closeBracket, $openBracket)),
            array(sprintf('foo%s:/%sbar%s', $closeBracket, $openBracket, $closeBracket), sprintf('foo%s:/%sbar%s', $closeBracket, $openBracket, $closeBracket)),
            array(sprintf('%s  foo  %s:/%s  bar  %s', $openBracket, $closeBracket, $openBracket, $closeBracket), sprintf('%s  foo  %s<img src="/images/hm.png" alt="">%s  bar  %s', $openBracket, $closeBracket, $openBracket, $closeBracket)),

            // With a smiley glue to the left and right of a tag
            array(sprintf(':/%s  foo  %s:/', $openBracket, $closeBracket), sprintf('<img src="/images/hm.png" alt="">%s  foo  %s<img src="/images/hm.png" alt="">', $openBracket, $closeBracket)),

            // Within brackets
            array('[quote=milesj]Hello, my name is [b]Miles Johnson[/b] :)[/quote] [b]Hello[/b] ;)', '[quote=milesj]Hello, my name is [b]Miles Johnson[/b] <img src="/images/happy.png" alt="">[/quote] [b]Hello[/b] <img src="/images/wink.png" alt="">'),
            array('[b]:)[/b]', '[b]<img src="/images/happy.png" alt="">[/b]'),
            array('[b] :)[/b]', '[b] <img src="/images/happy.png" alt="">[/b]'),
            array('[b]:) [/b]', '[b]<img src="/images/happy.png" alt=""> [/b]'),
            array('[b] :) [/b]', '[b] <img src="/images/happy.png" alt=""> [/b]'),
            array('[b] :][/b]', '[b] <img src="/images/happy.png" alt="">[/b]'),
            array('[b] :[[/b]', '[b] <img src="/images/sad.png" alt="">[/b]'),
            array('[b]:[ [/b]', '[b]<img src="/images/sad.png" alt=""> [/b]'),
            array('[b]:wink:[/b]', '[b]<img src="/images/wink.png" alt="">[/b]')
        );
    }

    /**
     * Test that smiley faces are converted to emoticon images.
     *
     * @dataProvider getSmileyConversionData
     */
    public function testSmileyConversion($value, $expected) {
        $this->assertEquals($expected, $this->object->beforeParse($value));
    }

    /**
     * Provide a mapping of smiley and rendered form.
     *
     * @return array
     */
    public function getSmileyConversionData() {
        $decoda = new Decoda();
        $hook = new EmoticonHook();
        $hook->setParser($decoda);
        $hook->startup();

        $data = array();

        foreach ($hook->getSmilies() as $smile) {
            $data[] = array($smile, $hook->render($smile, $decoda->getConfig('xhtmlOutput')));
        }

        return $data;
    }

    /**
     * Test callback back compatibility.
     *
     * @dataProvider getSmileyDetectionData
     */
    public function testEmoticonCallbackBackCompatibility($value, $expected) {
        $hook = new EmoticonHookBC();
        $hook->setParser($this->object->getParser());
        foreach ($this->object->getLoaders() as $loader) {
            $hook->addLoader($loader);
        }
        $hook->startup();

        $this->assertEquals($expected, $hook->beforeParse($value));
    }
}
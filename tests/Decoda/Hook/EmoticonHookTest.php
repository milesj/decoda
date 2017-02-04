<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Filter\DefaultFilter;
use Decoda\Hook\EmoticonHook;
use Decoda\Test\TestCase;
use Decoda\Loader\DataLoader;

class EmoticonHookTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->setBrackets('[', ']');
        $this->object->setLineBreaks(true);
        $this->object->setXhtml(false);

        $this->object->addFilter(new DefaultFilter());

        $hook = new EmoticonHook();
        $hook->addLoader(new DataLoader(array(
            'test/tag/within' => array('[o]_[o]'),
            'test/tag/open'   => array('['),
            'test/tag/close'  => array(']'),
            'test/unicode'    => array("\342\230\272"),
        )));

        $this->object->addHook($hook);
    }

    /**
     * Test smiley detection according to the positioning.
     *
     * @dataProvider getSmileyDetectionData
     */
    public function testSmileyDetection($value, $expected) {
        $this->assertEquals($expected, $this->object->reset($value)->parse());
    }

    /**
     * Provide all smiley patterns.
     *
     * @return array
     */
    public function getSmileyDetectionData() {
        return array(
            array(':/ at the beginning', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> at the beginning'),
            array('Smiley at the end :O', 'Smiley at the end <img class="decoda-emoticon" src="/images/gah.png" alt="">'),
            array('Smiley in the middle :P of a string', 'Smiley in the middle <img class="decoda-emoticon" src="/images/tongue.png" alt=""> of a string'),
            array(':):):)', '<img class="decoda-emoticon" src="/images/happy.png" alt=""><img class="decoda-emoticon" src="/images/happy.png" alt=""><img class="decoda-emoticon" src="/images/happy.png" alt="">'),
            array('At the :)start of the word', 'At the <img class="decoda-emoticon" src="/images/happy.png" alt="">start of the word'),
            array('At the mid:)dle of the word', 'At the mid:)dle of the word'),
            array('At the end:) of the word', 'At the end<img class="decoda-emoticon" src="/images/happy.png" alt=""> of the word'),
            array('At the miD:dle and end of the word D: ', 'At the miD:dle and end of the word <img class="decoda-emoticon" src="/images/gah.png" alt="">'),
            array('http://', 'http://'),
            array("With a :/\n linefeed", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt=""><br> linefeed'),
            array("With a :/\r carriage return", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt=""><br> carriage return'),
            array("With a :/\t tab", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt="">' . "\t" . ' tab'),
            array(':/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'),
            array(':/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'),
            array(':/ :/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'),
            array('Testing custom emoticon [', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/open.png" alt="">'),
            array('Testing custom emoticon ]', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/close.png" alt="">'),
            array('Testing custom emoticon [o]_[o]', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/within.png" alt="">'),
            array('[ b ] :/[ / b ]', '<b> <img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'),
            array('[ b ]:/ [ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""> </b>'),
            array('[ b ]:/[ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'),
            array('[ b ][[ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/open.png" alt=""></b>'),
            array('[ b ]][ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/close.png" alt=""></b>'),
            array('[ b ][o]_[o][ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/within.png" alt=""></b>'),
            array(':/[ b ]:/[ / b ]:/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""><b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b><img class="decoda-emoticon" src="/images/hm.png" alt="">'),
            array('[ b ]:/[ b ]:/[ / b ]:/[ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""><b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'),
            array('Testing custom emoticon ☺ (unicode)', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/unicode.png" alt=""> (unicode)'),
        );
    }

    /**
     * Test that smiley faces are converted to emoticon images.
     *
     * @dataProvider getSmileyConversionData
     */
    public function testSmileyConversion($value, $expected) {
        $this->assertEquals($expected, $this->object->reset($value)->parse());
    }

    /**
     * Provide a mapping of smiley and rendered form.
     *
     * @return array
     */
    public function getSmileyConversionData() {
        $this->setUp();
        $hook = $this->object->getHook('Emoticon');
        $hook->startup();

        $data = array();

        foreach ($hook->getSmilies() as $smile) {
            $data[] = array($smile, $hook->render($smile, $this->object->getConfig('xhtmlOutput')));
        }

        return $data;
    }
}

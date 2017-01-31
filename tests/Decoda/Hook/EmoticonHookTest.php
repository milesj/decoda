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
            array(':/ at the beginning', '<img class="emoticon" src="/images/hm.png" alt=""> at the beginning'),
            array('Smiley at the end :O', 'Smiley at the end <img class="emoticon" src="/images/gah.png" alt="">'),
            array('Smiley in the middle :P of a string', 'Smiley in the middle <img class="emoticon" src="/images/tongue.png" alt=""> of a string'),
            array(':):):)', '<img class="emoticon" src="/images/happy.png" alt=""><img class="emoticon" src="/images/happy.png" alt=""><img class="emoticon" src="/images/happy.png" alt="">'),
            array('At the :)start of the word', 'At the <img class="emoticon" src="/images/happy.png" alt="">start of the word'),
            array('At the mid:)dle of the word', 'At the mid:)dle of the word'),
            array('At the end:) of the word', 'At the end<img class="emoticon" src="/images/happy.png" alt=""> of the word'),
            array('At the miD:dle and end of the word D: ', 'At the miD:dle and end of the word <img class="emoticon" src="/images/gah.png" alt="">'),
            array('http://', 'http://'),
            array("With a :/\n linefeed", 'With a <img class="emoticon" src="/images/hm.png" alt=""><br> linefeed'),
            array("With a :/\r carriage return", 'With a <img class="emoticon" src="/images/hm.png" alt=""><br> carriage return'),
            array("With a :/\t tab", 'With a <img class="emoticon" src="/images/hm.png" alt="">' . "\t" . ' tab'),
            array(':/ :/', '<img class="emoticon" src="/images/hm.png" alt=""> <img class="emoticon" src="/images/hm.png" alt="">'),
            array(':/ :/', '<img class="emoticon" src="/images/hm.png" alt=""> <img class="emoticon" src="/images/hm.png" alt="">'),
            array(':/ :/ :/', '<img class="emoticon" src="/images/hm.png" alt=""> <img class="emoticon" src="/images/hm.png" alt=""> <img class="emoticon" src="/images/hm.png" alt="">'),
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

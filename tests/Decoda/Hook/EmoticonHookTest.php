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
    protected function setUp(): void {
        parent::setUp();

        $this->object->setBrackets('[', ']');
        $this->object->setLineBreaks(true);
        $this->object->setXhtml(false);

        $this->object->addFilter(new DefaultFilter());

        $hook = new EmoticonHook();
        $hook->addLoader(new DataLoader([
            'test/tag/within' => ['[o]_[o]'],
            'test/tag/open'   => ['['],
            'test/tag/close'  => [']'],
            'test/unicode'    => ["\342\230\272"],
        ]));

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
        return [
            [':/ at the beginning', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> at the beginning'],
            ['Smiley at the end :O', 'Smiley at the end <img class="decoda-emoticon" src="/images/gah.png" alt="">'],
            ['Smiley in the middle :P of a string', 'Smiley in the middle <img class="decoda-emoticon" src="/images/tongue.png" alt=""> of a string'],
            [':):):)', '<img class="decoda-emoticon" src="/images/happy.png" alt=""><img class="decoda-emoticon" src="/images/happy.png" alt=""><img class="decoda-emoticon" src="/images/happy.png" alt="">'],
            ['At the :)start of the word', 'At the <img class="decoda-emoticon" src="/images/happy.png" alt="">start of the word'],
            ['At the mid:)dle of the word', 'At the mid:)dle of the word'],
            ['At the end:) of the word', 'At the end<img class="decoda-emoticon" src="/images/happy.png" alt=""> of the word'],
            ['At the miD:dle and end of the word D: ', 'At the miD:dle and end of the word <img class="decoda-emoticon" src="/images/gah.png" alt="">'],
            ['http://', 'http://'],
            ["With a :/\n linefeed", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt=""><br> linefeed'],
            ["With a :/\r carriage return", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt=""><br> carriage return'],
            ["With a :/\t tab", 'With a <img class="decoda-emoticon" src="/images/hm.png" alt="">' . "\t" . ' tab'],
            [':/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'],
            [':/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'],
            [':/ :/ :/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt=""> <img class="decoda-emoticon" src="/images/hm.png" alt="">'],
            ['Testing custom emoticon [', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/open.png" alt="">'],
            ['Testing custom emoticon ]', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/close.png" alt="">'],
            ['Testing custom emoticon [o]_[o]', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/tag/within.png" alt="">'],
            ['[ b ] :/[ / b ]', '<b> <img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'],
            ['[ b ]:/ [ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""> </b>'],
            ['[ b ]:/[ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'],
            ['[ b ][[ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/open.png" alt=""></b>'],
            ['[ b ]][ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/close.png" alt=""></b>'],
            ['[ b ][o]_[o][ / b ]', '<b><img class="decoda-emoticon" src="/images/test/tag/within.png" alt=""></b>'],
            [':/[ b ]:/[ / b ]:/', '<img class="decoda-emoticon" src="/images/hm.png" alt=""><b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b><img class="decoda-emoticon" src="/images/hm.png" alt="">'],
            ['[ b ]:/[ b ]:/[ / b ]:/[ / b ]', '<b><img class="decoda-emoticon" src="/images/hm.png" alt=""><b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b><img class="decoda-emoticon" src="/images/hm.png" alt=""></b>'],
            ['Testing custom emoticon ☺ (unicode)', 'Testing custom emoticon <img class="decoda-emoticon" src="/images/test/unicode.png" alt=""> (unicode)'],
        ];
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

        $data = [];

        foreach ($hook->getSmilies() as $smile) {
            $data[] = [$smile, $hook->render($smile, $this->object->getConfig('xhtmlOutput'))];
        }

        return $data;
    }
}

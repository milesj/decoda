<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Filter\EmailFilter;
use Decoda\Filter\UrlFilter;
use Decoda\Hook\ClickableHook;
use Decoda\Test\TestCase;

class ClickableHookTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $decoda = new Decoda();
        $decoda->addFilter(new EmailFilter(array('encrypt' => false)));
        $decoda->addFilter(new UrlFilter());

        $this->object = new ClickableHook();
        $this->object->setParser($decoda);
    }

    /**
     * Test that afterParse() wraps URLs with anchor tags.
     */
    public function testUrlParsing() {
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->afterParse('http://domain.com'));
        $this->assertEquals('<a href="https://domain.com">https://domain.com</a>', $this->object->afterParse('https://domain.com'));
        $this->assertEquals('<a href="ftp://domain.com">ftp://domain.com</a>', $this->object->afterParse('ftp://domain.com'));
        $this->assertEquals('<a href="irc://domain.com">irc://domain.com</a>', $this->object->afterParse('irc://domain.com'));
        $this->assertEquals('<a href="http://domain.com:1337">http://domain.com:1337</a>', $this->object->afterParse('http://domain.com:1337'));
        $this->assertEquals('<a href="http://user:pass@domain.com">http://user:pass@domain.com</a>', $this->object->afterParse('http://user:pass@domain.com'));
        $this->assertEquals('<a href="http://domain.com?query=string&amp;key=value">http://domain.com?query=string&key=value</a>', $this->object->afterParse('http://domain.com?query=string&key=value'));
        $this->assertEquals('<a href="http://domain.com#fragment">http://domain.com#fragment</a>', $this->object->afterParse('http://domain.com#fragment'));

        // positioning
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a> at the beginning', $this->object->afterParse('http://domain.com at the beginning'));
        $this->assertEquals('URL at the end <a href="ftp://domain.com">ftp://domain.com</a>', $this->object->afterParse('URL at the end ftp://domain.com'));
        $this->assertEquals('URL in the middle <a href="https://domain.com">https://domain.com</a> of a string', $this->object->afterParse('URL in the middle https://domain.com of a string'));

        // test that it doesn't grab URLs from within anchor tags
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->afterParse('<a href="http://domain.com">http://domain.com</a>'));

        // invalid urls
        $this->assertEquals('http:domain.com', $this->object->afterParse('http:domain.com'));
        $this->assertEquals('file://image.png', $this->object->afterParse('file://image.png'));
    }

    /**
     * Test that afterParse() wraps emails with anchor tags.
     */
    public function testEmailParsing() {
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a>', $this->object->afterParse('email@domain.com'));
        $this->assertEquals('<a href="mailto:email+group@domain.com">email+group@domain.com</a>', $this->object->afterParse('email+group@domain.com'));
        $this->assertEquals('<a href="mailto:email-dashed@domain.co.uk">email-dashed@domain.co.uk</a>', $this->object->afterParse('email-dashed@domain.co.uk'));

        // positioning
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a> at the beginning', $this->object->afterParse('email@domain.com at the beginning'));
        $this->assertEquals('Email at the end <a href="mailto:email@domain.com">email@domain.com</a>', $this->object->afterParse('Email at the end email@domain.com'));
        $this->assertEquals('Email in the middle <a href="mailto:email@domain.com">email@domain.com</a> of a string', $this->object->afterParse('Email in the middle email@domain.com of a string'));

        // test that it doesn't grab emails from within anchor tags
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a>', $this->object->afterParse('<a href="mailto:email@domain.com">email@domain.com</a>'));

        // invalid emails
        $this->assertEquals('email@domain', $this->object->afterParse('email@domain'));
    }

}
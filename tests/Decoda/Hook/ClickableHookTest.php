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
     * Test that beforeParse() wraps URLs with anchor tags.
     */
    public function testUrlParsing() {
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->beforeParse('http://domain.com'));
        $this->assertEquals('<a href="https://domain.com">https://domain.com</a>', $this->object->beforeParse('https://domain.com'));
        $this->assertEquals('<a href="ftp://domain.com">ftp://domain.com</a>', $this->object->beforeParse('ftp://domain.com'));
        $this->assertEquals('<a href="irc://domain.com">irc://domain.com</a>', $this->object->beforeParse('irc://domain.com'));
        $this->assertEquals('<a href="http://domain.com:1337">http://domain.com:1337</a>', $this->object->beforeParse('http://domain.com:1337'));
        $this->assertEquals('<a href="http://user:pass@domain.com">http://user:pass@domain.com</a>', $this->object->beforeParse('http://user:pass@domain.com'));
        $this->assertEquals('<a href="http://domain.com?query=string&amp;key=value">http://domain.com?query=string&key=value</a>', $this->object->beforeParse('http://domain.com?query=string&key=value'));
        $this->assertEquals('<a href="http://domain.com#fragment">http://domain.com#fragment</a>', $this->object->beforeParse('http://domain.com#fragment'));

        // positioning
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a> at the beginning', $this->object->beforeParse('http://domain.com at the beginning'));
        $this->assertEquals('URL at the end <a href="ftp://domain.com">ftp://domain.com</a>', $this->object->beforeParse('URL at the end ftp://domain.com'));
        $this->assertEquals('URL in the middle <a href="https://domain.com">https://domain.com</a> of a string', $this->object->beforeParse('URL in the middle https://domain.com of a string'));

        // test that it doesn't grab URLs from url tags
        $this->assertEquals('[img]http://domain.com/x.png[/img]', $this->object->beforeParse('[img]http://domain.com/x.png[/img]'));
        $this->assertEquals('[url="http://domain.com"]http://domain.com[/url]', $this->object->beforeParse('[url="http://domain.com"]http://domain.com[/url]'));
        $this->assertEquals('[url="http://domain.com"]text[/url]', $this->object->beforeParse('[url="http://domain.com"]text[/url]'));

        // invalid urls
        $this->assertEquals('http:domain.com', $this->object->beforeParse('http:domain.com'));
        $this->assertEquals('file://image.png', $this->object->beforeParse('file://image.png'));
    }

    /**
     * Test that beforeParse() wraps emails with anchor tags.
     */
    public function testEmailParsing() {
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a>', $this->object->beforeParse('email@domain.com'));
        $this->assertEquals('<a href="mailto:email+group@domain.com">email+group@domain.com</a>', $this->object->beforeParse('email+group@domain.com'));
        $this->assertEquals('<a href="mailto:email-dashed@domain.co.uk">email-dashed@domain.co.uk</a>', $this->object->beforeParse('email-dashed@domain.co.uk'));

        // positioning
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a> at the beginning', $this->object->beforeParse('email@domain.com at the beginning'));
        $this->assertEquals('Email at the end <a href="mailto:email@domain.com">email@domain.com</a>', $this->object->beforeParse('Email at the end email@domain.com'));
        $this->assertEquals('Email in the middle <a href="mailto:email@domain.com">email@domain.com</a> of a string', $this->object->beforeParse('Email in the middle email@domain.com of a string'));

        // invalid emails
        $this->assertEquals('email@domain', $this->object->beforeParse('email@domain'));
    }

}

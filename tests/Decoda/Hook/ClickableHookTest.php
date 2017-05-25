<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Filter\DefaultFilter;
use Decoda\Filter\EmailFilter;
use Decoda\Filter\UrlFilter;
use Decoda\Hook\ClickableHook;
use Decoda\Test\TestCase;

class ClickableHookTest extends TestCase {

    protected $hook;

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new DefaultFilter());
        $this->object->addFilter(new EmailFilter(array('encrypt' => false)));
        $this->object->addFilter(new UrlFilter());

        $this->hook = new ClickableHook();
        $this->object->addHook($this->hook);
    }

    /**
     * Test that beforeParse() wraps URLs with anchor tags.
     */
    public function testUrlParsing() {
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->reset('http://domain.com')->parse());
        $this->assertEquals('<a href="https://domain.com">https://domain.com</a>', $this->object->reset('https://domain.com')->parse());
        $this->assertEquals('<a href="ftp://domain.com">ftp://domain.com</a>', $this->object->reset('ftp://domain.com')->parse());
        $this->assertEquals('<a href="irc://domain.com">irc://domain.com</a>', $this->object->reset('irc://domain.com')->parse());
        $this->assertEquals('<a href="http://domain.com:1337">http://domain.com:1337</a>', $this->object->reset('http://domain.com:1337')->parse());
        $this->assertEquals('<a href="http://user:pass@domain.com">http://user:pass@domain.com</a>', $this->object->reset('http://user:pass@domain.com')->parse());
        $this->assertEquals('<a href="http://domain.com?query=string&amp;key=value">http://domain.com?query=string&amp;key=value</a>', $this->object->reset('http://domain.com?query=string&key=value')->parse());
        $this->assertEquals('<a href="http://domain.com#fragment">http://domain.com#fragment</a>', $this->object->reset('http://domain.com#fragment')->parse());

        // positioning
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a> at the beginning', $this->object->reset('http://domain.com at the beginning')->parse());
        $this->assertEquals('URL at the end <a href="ftp://domain.com">ftp://domain.com</a>', $this->object->reset('URL at the end ftp://domain.com')->parse());
        $this->assertEquals('URL in the middle <a href="https://domain.com">https://domain.com</a> of a string', $this->object->reset('URL in the middle https://domain.com of a string')->parse());

        // test that it doesn't grab URLs from url or img tags
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->reset('[url="http://domain.com"]http://domain.com[/url]')->parse());
        $this->assertEquals('<a href="http://domain.com">text</a>', $this->object->reset('[url="http://domain.com"]text[/url]')->parse());
        $this->assertEquals('[img]http://domain.com/x.png[/img]', $this->object->reset('[img]http://domain.com/x.png[/img]')->parse());

        // test url mixed with other bb tags
        $this->assertEquals('<b><a href="http://domain.com">http://domain.com</a></b>', $this->object->reset('[b]http://domain.com[/b]')->parse());
        $this->assertEquals('<a href="http://domain.com"><b>http://domain.com</b></a>', $this->object->reset('[url="http://domain.com"][b]http://domain.com[/b][/url]')->parse());

        // test some advanced urls
        $this->assertEquals('<a href="http://www.domain.com?x=1&amp;y=2#fragment">http://www.domain.com?x=1&amp;y=2#fragment</a>', $this->object->reset('http://www.domain.com?x=1&y=2#fragment')->parse());
        $this->assertEquals('<a href="http://www.domain.com/?x=1&amp;y=2#fragment">http://www.domain.com/?x=1&amp;y=2#fragment</a>', $this->object->reset('http://www.domain.com/?x=1&y=2#fragment')->parse());
        $this->assertEquals('<a href="http://www.domain.com/some/deep/path/?x=1&amp;y=2#fragment">http://www.domain.com/some/deep/path/?x=1&amp;y=2#fragment</a>', $this->object->reset('http://www.domain.com/some/deep/path/?x=1&y=2#fragment')->parse());
        $this->assertEquals('<a href="http://www.domain.com/some/deep/path/#fragment">http://www.domain.com/some/deep/path/#fragment</a>', $this->object->reset('http://www.domain.com/some/deep/path/#fragment')->parse());

        // test email url link
        $this->assertEquals('<a href="mailto:test@email.com">test@email.com</a>', $this->object->reset('[url="mailto:test@email.com"]test@email.com[/url]')->parse());

        // url without http://
        $this->assertEquals('This should be a link: <a href="http://www.domain.com">www.domain.com</a>', $this->object->reset('This should be a link: www.domain.com')->parse());

        // test that ClickableHook does not interfere with other html tags
        $this->assertEquals('<br/><a href="http://domain.com">http://domain.com</a><br/>', $this->hook->beforeParse('<br/>http://domain.com<br/>'));

        // invalid urls
        $this->assertEquals('http:domain.com', $this->object->reset('http:domain.com')->parse());
        $this->assertEquals('file://image.png', $this->object->reset('file://image.png')->parse());
    }

    /**
     * Test that beforeParse() wraps emails with anchor tags.
     */
    public function testEmailParsing() {
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a>', $this->object->reset('email@domain.com')->parse());
        $this->assertEquals('<a href="mailto:email+group@domain.com">email+group@domain.com</a>', $this->object->reset('email+group@domain.com')->parse());
        $this->assertEquals('<a href="mailto:email-dashed@domain.co.uk">email-dashed@domain.co.uk</a>', $this->object->reset('email-dashed@domain.co.uk')->parse());

        // positioning
        $this->assertEquals('<a href="mailto:email@domain.com">email@domain.com</a> at the beginning', $this->object->reset('email@domain.com at the beginning')->parse());
        $this->assertEquals('Email at the end <a href="mailto:email@domain.com">email@domain.com</a>', $this->object->reset('Email at the end email@domain.com')->parse());
        $this->assertEquals('Email in the middle <a href="mailto:email@domain.com">email@domain.com</a> of a string', $this->object->reset('Email in the middle email@domain.com of a string')->parse());

        // invalid emails
        $this->assertEquals('email@domain', $this->object->reset('email@domain')->parse());
    }

}

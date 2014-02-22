<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\UrlFilter;
use Decoda\Test\TestCase;

class UrlFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new UrlFilter());
    }

    /**
     * Test that [url] renders anchor links and validates URLs.
     */
    public function testUrl() {
        $this->assertEquals('<a href="http://domain.com">http://domain.com</a>', $this->object->reset('[url]http://domain.com[/url]')->parse());
        $this->assertEquals('<a href="https://domain.com">https://domain.com</a>', $this->object->reset('[url]https://domain.com[/url]')->parse());
        $this->assertEquals('<a href="ftp://domain.com">ftp://domain.com</a>', $this->object->reset('[url]ftp://domain.com[/url]')->parse());
        $this->assertEquals('<a href="irc://domain.com">irc://domain.com</a>', $this->object->reset('[url]irc://domain.com[/url]')->parse());

        $this->assertEquals('<a href="http://domain.com">Link</a>', $this->object->reset('[url="http://domain.com"]Link[/url]')->parse());
        $this->assertEquals('<a href="http://domain.com?query=string">Link</a>', $this->object->reset('[url="http://domain.com?query=string"]Link[/url]')->parse());
        $this->assertEquals('<a href="https://domain.com?query=string&amp;key=value">Link</a>', $this->object->reset('[url="https://domain.com?query=string&key=value"]Link[/url]')->parse());
        $this->assertEquals('<a href="http://domain.com?query=string&amp;key=value#frag">Link</a>', $this->object->reset('[url="http://domain.com?query=string&key=value#frag"]Link[/url]')->parse());
        $this->assertEquals('<a href="ftps://user:pass@domain.com?query=string&amp;key=value">Link</a>', $this->object->reset('[url="ftps://user:pass@domain.com?query=string&key=value"]Link[/url]')->parse());
        $this->assertEquals('<a href="http://domain.com:8080?query=string&amp;key=value">Link</a>', $this->object->reset('[url="http://domain.com:8080?query=string&key=value"]Link[/url]')->parse());

        // Invalid
        $this->assertEquals('http:domain.com', $this->object->reset('[url]http:domain.com[/url]')->parse());
        $this->assertEquals('file://image.png', $this->object->reset('[url]file://image.png[/url]')->parse());
        $this->assertEquals('ssh://domain.com/some/url', $this->object->reset('[url="ssh://domain.com/some/url"]SSH[/url]')->parse());

        // Test URLs with a trailing slash
        $this->assertEquals('<a href="http://domain.com/">http://domain.com/</a>', $this->object->reset('[url]http://domain.com/[/url]')->parse());
        $this->assertEquals('<a href="http://domain.com/">Test</a>', $this->object->reset('[url="http://domain.com/"]Test[/url]')->parse());

        // Allow URLs with missing protocol
        $this->assertEquals('<a href="http://domain.com">domain.com</a>', $this->object->reset('[url]domain.com[/url]')->parse());
        $this->assertEquals('<a href="http://www.domain.com">www.domain.com</a>', $this->object->reset('[url]www.domain.com[/url]')->parse());

        // Allow relative and absolute paths
        $this->assertEquals('<a href="/absolute/directory">/absolute/directory</a>', $this->object->reset('[url]/absolute/directory[/url]')->parse());
        $this->assertEquals('<a href="./same/directory">./same/directory</a>', $this->object->reset('[url]./same/directory[/url]')->parse());
        $this->assertEquals('<a href="../relative/directory">../relative/directory</a>', $this->object->reset('[url]../relative/directory[/url]')->parse());
        $this->assertEquals('<a href="../../relative/again">../../relative/again</a>', $this->object->reset('[url]../../relative/again[/url]')->parse());
        $this->assertEquals('.../invalid/relative', $this->object->reset('[url].../invalid/relative[/url]')->parse());
    }

    /**
     * Test URLs with a trailing slash.
     */
    public function testUrlWhenStrictIsFalse() {
        $this->object->setStrict(false);
        $this->assertEquals('<a href="http://domain.com/">Test</a>', $this->object->reset('[url=http://domain.com/]Test[/url]')->parse());
    }

}
<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\EmailFilter;
use Decoda\Test\TestCase;

class EmailFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new EmailFilter());
    }

    /**
     * Test that [email] renders anchor links that send to email.
     */
    public function testEmail() {
        $expected = '<a href="mailto:&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;</a>';
        $this->assertEquals($expected, $this->object->reset('[email]email@domain.com[/email]')->parse());

        $expected = '<a href="mailto:&#101;&#109;&#97;&#105;&#108;&#64;&#100;&#111;&#109;&#97;&#105;&#110;&#46;&#99;&#111;&#109;">Email me!</a>';
        $this->assertEquals($expected, $this->object->reset('[email="email@domain.com"]Email me![/email]')->parse());

        $expected = 'email@domain';
        $this->assertEquals($expected, $this->object->reset('[email]email@domain[/email]')->parse());

        $expected = 'Email me!';
        $this->assertEquals($expected, $this->object->reset('[email="email@domain"]Email me![/email]')->parse());
    }

}
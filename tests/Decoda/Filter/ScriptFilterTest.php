<?php
/**
 * @copyright   2017, RIGAUDIE David - http://rigaudie.fr
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\ScriptFilter;
use Decoda\Test\TestCase;

class ScriptFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new ScriptFilter());
    }

    /**
     * Test that [script] renders script tag with src attribute.
     */
    public function testScript() {
        // Upper case
        $this->assertEquals(
            '<script src="http://domain.com/link.js"></script>',
            $this->object->reset('[SCRIPT src="http://domain.com/link.js"][/SCRIPT]')->parse()
        );

        // http
        $this->assertEquals(
            '<script src="http://domain.com/link.js"></script>',
            $this->object->reset('[script src="http://domain.com/link.js"][/script]')->parse()
        );

        // https
        $this->assertEquals(
            '<script src="https://domain.com/link.js"></script>',
            $this->object->reset('[script src="https://domain.com/link.js"][/script]')->parse()
        );

        // Not extension file
        $this->assertEquals(
            '<script src="https://domain.com/link"></script>',
            $this->object->reset('[script src="https://domain.com/link"][/script]')->parse()
        );


        // No protocol, absolute path
        $this->assertEquals(
            '<script src="//domain.com/link.JS"></script>',
            $this->object->reset('[script src="//domain.com/link.JS"][/script]')->parse()
        );

        // No protocol, relative path
        $this->assertEquals(
            '<script src="../images/link.js"></script>',
            $this->object->reset('[script src="../images/link.js"][/script]')->parse()
        ); 

        // Security
        $this->assertEquals(
            null,
            $this->object->reset('[script src="not url path"][/script]')->parse()
        );

        //Wrong bbcode
        $this->assertEquals(
            null,
            $this->object->reset('[script src="https://domain.com/link.js"]')->parse()
        );
        $this->assertEquals(
            null,
            $this->object->reset('[script src="https://domain.com/link.js"/]')->parse()
        );
    }

}
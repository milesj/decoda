<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Test\TestCase;
use Decoda\Test\TestHook;

class HookTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new TestHook();
    }

    /**
     * Test that beforeParse() alters the string.
     */
    public function testBeforeParse() {
        $this->assertEquals('baa2e91e038e0742f98c9b0836dc0065', $this->object->beforeParse('beforeParse'));
    }

    /**
     * Test that afterParse() alters the string.
     */
    public function testAfterParse() {
        $this->assertEquals('b146230f63474e50b8eb9e232c2b6542', $this->object->afterParse('afterParse'));
    }

}
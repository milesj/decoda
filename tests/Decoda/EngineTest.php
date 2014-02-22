<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Test\TestCase;
use Decoda\Test\TestEngine;
use Decoda\Test\TestFilter;

class EngineTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new TestEngine();
    }

    /**
     * Test setPath() sets a path, and getPath() returns it.
     */
    public function testGetAddPath() {
        $this->assertEquals(array(), $this->object->getPaths());

        $this->object->addPath(DECODA . 'tpls');
        $this->assertEquals(array(DECODA . 'tpls/'), $this->object->getPaths());
    }

    /**
     * Test that setFilter() sets a Filter, and getFilter() returns it.
     */
    public function testGetSetFilter() {
        $this->assertEquals(null, $this->object->getFilter());

        $this->object->setFilter(new TestFilter());
        $this->assertInstanceOf('Decoda\Filter', $this->object->getFilter());
    }

}
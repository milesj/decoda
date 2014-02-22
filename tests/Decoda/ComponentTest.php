<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Loader\FileLoader;
use Decoda\Test\TestCase;
use Decoda\Test\TestComponent;

class ComponentTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new TestComponent(array('key' => 'value'));
    }

    /**
     * Test that adding and getting loaders work.
     */
    public function testAddGetLoaders() {
        $this->assertEquals(0, count($this->object->getLoaders()));

        $this->object->addLoader(new FileLoader(TEST_DIR . '/config/test.php'));
        $this->assertEquals(1, count($this->object->getLoaders()));
    }

    /**
     * Test that getConfig() returns a configuration value and setConfig() writes it.
     */
    public function testGetSetConfig() {
        $this->assertEquals('value', $this->object->getConfig('key'));
        $this->assertEquals(null, $this->object->getConfig('foobar'));

        $this->object->setConfig(array('key' => 'foo'));
        $this->assertEquals('foo', $this->object->getConfig('key'));
    }

    /**
     * Test that setParser() sets Decoda and getParser() returns it.
     */
    public function testGetSetParser() {
        $this->assertEquals(null, $this->object->getParser());

        $this->object->setParser(new Decoda());
        $this->assertInstanceOf('Decoda\Decoda', $this->object->getParser());
    }

    /**
     * Test that message() returns a localized string.
     */
    public function testMessage() {
        $this->object->setParser(new Decoda());

        $this->assertEquals('Quote by {author}', $this->object->message('quoteBy'));
    }

}
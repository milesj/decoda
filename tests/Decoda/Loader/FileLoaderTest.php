<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Loader;

use Decoda\Decoda;
use Decoda\Loader\FileLoader;
use Decoda\Test\TestCase;
use \Exception;

class FileLoaderTest extends TestCase {

    /**
     * Test that exceptions are thrown correctly.
     */
    public function testExceptions() {
        try {
            $object = new FileLoader(TEST_DIR . '/config/test.tmp');
            $this->assertFalse(true);

        } catch (Exception $e) {
            $this->assertTrue(true, $e->getMessage());
        }

        try {
            $object = new FileLoader(TEST_DIR . '/config/test.xml');
            $object->load();
            $this->assertFalse(true);

        } catch (Exception $e) {
            $this->assertTrue(true, $e->getMessage());
        }
    }

    /**
     * Test that PHP files can be read.
     */
    public function testPhpRead() {
        $object = new FileLoader(TEST_DIR . '/config/test.php');
        $this->assertEquals(array('foo' => 'bar'), $object->load());
    }

    /**
     * Test that JSON files can be read.
     */
    public function testJsonRead() {
        $object = new FileLoader(TEST_DIR . '/config/test.json');
        $this->assertEquals(array('foo' => 'bar'), $object->load());
    }

    /**
     * Test that INI files can be read.
     */
    public function testIniRead() {
        $object = new FileLoader(TEST_DIR . '/config/test.ini');
        $this->assertEquals(array('foo' => 'bar'), $object->load());
    }

    /**
     * Test that TXT files can be read.
     */
    public function testTxtRead() {
        $object = new FileLoader(TEST_DIR . '/config/test.txt');
        $this->assertEquals(array('foo', 'bar'), $object->load());
    }

}
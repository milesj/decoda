<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
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
			$object = new FileLoader(dirname(__DIR__) . '/config/test.tmp');
			$this->assertFalse(true);

		} catch (Exception $e) {
			$this->assertTrue(true, $e->getMessage());
		}

		try {
			$object = new FileLoader(dirname(__DIR__) . '/config/test.xml');
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
		$object = new FileLoader(dirname(__DIR__) . '/config/test.php');
		$this->assertEquals(array('foo' => 'bar'), $object->load());
	}

	/**
	 * Test that JSON files can be read.
	 */
	public function testJsonRead() {
		$object = new FileLoader(dirname(__DIR__) . '/config/test.json');
		$this->assertEquals(array('foo' => 'bar'), $object->load());
	}

	/**
	 * Test that INI files can be read.
	 */
	public function testIniRead() {
		$object = new FileLoader(dirname(__DIR__) . '/config/test.ini');
		$this->assertEquals(array('foo' => 'bar'), $object->load());
	}

	/**
	 * Test that TXT files can be read.
	 */
	public function testTxtRead() {
		$object = new FileLoader(dirname(__DIR__) . '/config/test.txt');
		$this->assertEquals(array('foo', 'bar'), $object->load());
	}

}
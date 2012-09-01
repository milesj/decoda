<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\engines;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\engines\PhpEngine;
use mjohnson\decoda\tests\TestCase;
use mjohnson\decoda\tests\TestFilter;

class PhpEngineTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new PhpEngine();
		$this->object->setPath(DECODA . 'tests/templates/');
		$this->object->setFilter(new TestFilter());
	}

	/**
	 * Test that render() renders a template and extracts attribute variables.
	 */
	public function testRender() {
		$this->assertEquals('foobar', $this->object->render(array(
			'tag' => 'template',
			'attributes' => array('var' => 'foobar')
		), null));

		$this->assertEquals('', $this->object->render(array(
			'tag' => 'template',
			'attributes' => array('var' => '')
		), null));

		try {
			$this->object->render(array(
				'tag' => 'templateMissing',
				'attributes' => array('var' => '')
			), null);

			$this->assertTrue(false);
		} catch (\Exception $e) {
			$this->assertTrue(true);
		}
	}

}
<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests;

use mjohnson\decoda\Decoda;

class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * Decoda instance.
	 *
	 * @access protected
	 * @var object
	 */
	protected $object;

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		$this->object = new Decoda();
	}

	/**
	 * Strip new lines and tabs to test template files easily.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function clean($string) {
		return str_replace(array("\t", "\r", "\n"), '', $string);
	}

}
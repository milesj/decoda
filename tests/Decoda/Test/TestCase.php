<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Decoda;

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

	/**
	 * Convert newlines to \n.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public function nl($string) {
		return str_replace("\r", "", $string);
	}

}
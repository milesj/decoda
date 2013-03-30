<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Hook\AbstractHook;

class TestHook extends AbstractHook {

	/**
	 * MD5 the string for testing.
	 *
	 * @param string $string
	 * @return string
	 */
	public function beforeParse($string) {
		return md5($string);
	}

	/**
	 * MD5 the string for testing.
	 *
	 * @param string $string
	 * @return string
	 */
	public function afterParse($string) {
		return md5($string);
	}

}
<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Engine\AbstractEngine;

class TestEngine extends AbstractEngine {

	/**
	 * Render a template.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function render(array $tag, $content) {
		return '';
	}

}
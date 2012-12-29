<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\engines;

use mjohnson\decoda\engines\EngineAbstract;

/**
 * Renders tags by using PHP as template engine.
 *
 * @package	mjohnson.decoda.engines
 */
class PhpEngine extends EngineAbstract {

	/**
	 * Renders the tag by using PHP templates.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 * @throws \Exception
	 */
	public function render(array $tag, $content) {
		$setup = $this->getFilter()->tag($tag['tag']);
		$path = $this->getPath() . $setup['template'] . '.php';

		if (!file_exists($path)) {
			throw new \Exception(sprintf('Template file %s does not exist.', $setup['template']));
		}

		extract($tag['attributes'], EXTR_SKIP);
		ob_start();

		include $path;

		return trim(ob_get_clean());
	}

}
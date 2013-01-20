<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Filter;
use Decoda\Engine;

/**
 * Provides default methods for engines.
 */
abstract class AbstractEngine implements Engine {

	/**
	 * Current path.
	 *
	 * @var string
	 */
	protected $_path;

	/**
	 * Current filter.
	 *
	 * @var \Decoda\Filter
	 */
	protected $_filter;

	/**
	 * Return the current filter.
	 *
	 * @return \Decoda\Filter
	 */
	public function getFilter() {
		return $this->_filter;
	}

	/**
	 * Return the template path. If no path has been set, set it.
	 *
	 * @return string
	 */
	public function getPath() {
		if (!$this->_path) {
			$this->setPath(dirname(__DIR__) . '/templates/');
		}

		return $this->_path;
	}

	/**
	 * Sets the current filter.
	 *
	 * @param \Decoda\Filter $filter
	 * @return \Decoda\Engine
	 */
	public function setFilter(Filter $filter) {
		$this->_filter = $filter;

		return $this;
	}

	/**
	 * Sets the path to the tag templates.
	 *
	 * @param string $path
	 * @return \Decoda\Engine
	 */
	public function setPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}

		$this->_path = $path;

		return $this;
	}

}

<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Component\AbstractComponent;
use Decoda\Filter;
use Decoda\Engine;

/**
 * Provides default methods for engines.
 */
abstract class AbstractEngine extends AbstractComponent implements Engine {

	/**
	 * Lookup paths.
	 *
	 * @var array
	 */
	protected $_paths = array();

	/**
	 * Current filter.
	 *
	 * @var \Decoda\Filter
	 */
	protected $_filter;

	/**
	 * Add a template lookup path.
	 *
	 * @param string $path
	 * @return \Decoda\Engine
	 */
	public function addPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}

		$this->_paths[] = $path;

		return $this;
	}

	/**
	 * Return the current filter.
	 *
	 * @return \Decoda\Filter
	 */
	public function getFilter() {
		return $this->_filter;
	}

	/**
	 * Returns the paths to the templates.
	 *
	 * @return array
	 */
	public function getPaths() {
		return $this->_paths;
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

}

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
	 * @access protected
	 * @var string
	 */
	protected $_path;

	/**
	 * Current filter.
	 *
	 * @access protected
	 * @var \Decoda\Filter
	 */
	protected $_filter;

	/**
	 * Return the current filter.
	 *
	 * @access public
	 * @return \Decoda\Filter
	 */
	public function getFilter() {
		return $this->_filter;
	}

	/**
	 * Return the template path. If no path has been set, set it.
	 *
	 * @access public
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
	 * @access public
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
	 * @access public
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

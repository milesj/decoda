<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Filter\Filter;
use Decoda\Engine\Engine;

/**
 * Provides default methods for engines.
 *
 * @package	mjohnson.decoda.engines
 * @abstract
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
	 * @var \Decoda\Filter\Filter
	 */
	protected $_filter;

	/**
	 * Return the current filter.
	 *
	 * @access public
	 * @return \Decoda\Filter\Filter
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
			$this->setPath(DECODA . 'templates/');
		}

		return $this->_path;
	}

	/**
	 * Sets the current filter.
	 *
	 * @access public
	 * @param \Decoda\Filter\Filter $filter
	 * @return \Decoda\Engine\Engine
	 * @chainable
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
	 * @return \Decoda\Engine\Engine
	 * @chainable
	 */
	public function setPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}

		$this->_path = $path;

		return $this;
	}

}

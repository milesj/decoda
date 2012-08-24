<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\engines;

use mjohnson\decoda\filters\FilterInterface;
use mjohnson\decoda\engines\EngineInterface;

/**
 * Provides default methods for engines.
 *
 * @package	mjohnson.decoda.engines
 * @abstract
 */
abstract class EngineAbstract implements EngineInterface {

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
	 * @var \mjohnson\decoda\filters\FilterInterface
	 */
	protected $_filter;

	/**
	 * Return the current filter.
	 *
	 * @access public
	 * @return \mjohnson\decoda\filters\FilterInterface
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
		if (empty($this->_path)) {
			$this->setPath(DECODA . '/templates/');
		}

		return $this->_path;
	}

	/**
	 * Sets the current filter.
	 *
	 * @access public
	 * @param \mjohnson\decoda\filters\FilterInterface $filter
	 * @return \mjohnson\decoda\engines\EngineInterface
	 */
	public function setFilter(FilterInterface $filter) {
		$this->_filter = $filter;

		return $this;
	}

	/**
	 * Sets the path to the tag templates.
	 *
	 * @access public
	 * @param string $path
	 * @return \mjohnson\decoda\engines\EngineInterface
	 */
	public function setPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}

		$this->_path = $path;

		return $this;
	}

}

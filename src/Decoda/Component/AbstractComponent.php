<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Component;

use Decoda\Decoda;
use Decoda\Component;
use Decoda\Loader;

/**
 * Provides default shared functionality for Filters, Hooks and Engines.
 */
abstract class AbstractComponent implements Component {

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array();

	/**
	 * List of Loaders.
	 *
	 * @var \Decoda\Loader[]
	 */
	protected $_loaders = array();

	/**
	 * Decoda object.
	 *
	 * @var \Decoda\Decoda
	 */
	protected $_parser;

	/**
	 * Apply configuration.
	 *
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		$this->setConfig($config);
		$this->construct();
	}

	/**
	 * Add a loader.
	 *
	 * @param \Decoda\Loader $loader
	 * @return \Decoda\Component
	 */
	public function addLoader(Loader $loader) {
		$this->_loaders[] = $loader;

		return $this;
	}

	/**
	 * Method called immediately after the constructor.
	 *
	 * @return void
	 */
	public function construct() {
		return;
	}

	/**
	 * Return a specific configuration key value.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getConfig($key) {
		return isset($this->_config[$key]) ? $this->_config[$key] : null;
	}

	/**
	 * Return all the Loaders.
	 *
	 * @return \Decoda\Loader[]
	 */
	public function getLoaders() {
		return $this->_loaders;
	}

	/**
	 * Return the Decoda parser.
	 *
	 * @return \Decoda\Decoda
	 */
	public function getParser() {
		return $this->_parser;
	}

	/**
	 * Return a message string from the parser.
	 *
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array()) {
		return $this->getParser()->message($key, $vars);
	}

	/**
	 * Modify configuration.
	 *
	 * @param array $config
	 * @return \Decoda\Component
	 */
	public function setConfig(array $config) {
		$this->_config = $config + $this->_config;

		return $this;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Component
	 */
	public function setParser(Decoda $parser) {
		$this->_parser = $parser;

		return $this;
	}

}
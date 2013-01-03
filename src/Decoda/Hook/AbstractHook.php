<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Hook;

/**
 * A hook allows you to inject functionality during certain events in the parsing cycle.
 */
abstract class AbstractHook implements Hook {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array();

	/**
	 * Decoda object.
	 *
	 * @access protected
	 * @var \Decoda\Decoda
	 */
	protected $_parser;

	/**
	 * Apply configuration.
	 *
	 * @access public
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		$this->_config = $config + $this->_config;
	}

	/**
	 * Return a specific configuration key value.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function config($key) {
		return isset($this->_config[$key]) ? $this->_config[$key] : null;
	}

	/**
	 * Process the content after the parsing has finished.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterParse($content) {
		return $content;
	}

	/**
	 * Process the content after the stripping has finished.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterStrip($content) {
		return $content;
	}

	/**
	 * Process the content before the parsing begins.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return $content;
	}

	/**
	 * Process the content before the stripping begins.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeStrip($content) {
		return $content;
	}

	/**
	 * Return the Decoda parser.
	 *
	 * @access public
	 * @return \Decoda\Decoda
	 */
	public function getParser() {
		return $this->_parser;
	}

	/**
	 * Return a message string from the parser.
	 *
	 * @access public
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array()) {
		return $this->getParser()->message($key, $vars);
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Hook
	 */
	public function setParser(Decoda $parser) {
		$this->_parser = $parser;

		return $this;
	}

	/**
	 * Add any filter dependencies.
	 *
	 * @access public
	 * @param \Decoda\Decoda $decoda
	 * @return \Decoda\Hook
	 */
	public function setupFilters(Decoda $decoda) {
		return $this;
	}

}
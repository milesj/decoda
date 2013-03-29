<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;

/**
 * Defines the methods for all Components to implement.
 */
interface Component {

	/**
	 * Return a specific configuration key value.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getConfig($key);

	/**
	 * Return the Decoda parser.
	 *
	 * @return \Decoda\Decoda
	 */
	public function getParser();

	/**
	 * Return a message string from the parser.
	 *
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array());

	/**
	 * Modify configuration.
	 *
	 * @param array $config
	 * @return \Decoda\Component
	 */
	public function setConfig(array $config);

	/**
	 * Set the Decoda parser.
	 *
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Filter
	 */
	public function setParser(Decoda $parser);

}
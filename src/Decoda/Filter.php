<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;

/**
 * Defines the methods for all Filters to implement.
 */
interface Filter {

	/**
	 * Return a specific configuration key value.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function config($key);

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
	 * Parse the node and its content into an HTML tag.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content);

	/**
	 * Set the Decoda parser.
	 *
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Filter
	 */
	public function setParser(Decoda $parser);

	/**
	 * Add any hook dependencies.
	 *
	 * @param \Decoda\Decoda $decoda
	 * @return \Decoda\Filter
	 */
	public function setupHooks(Decoda $decoda);

	/**
	 * Strip a node and remove content dependent on settings.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function strip(array $tag, $content);

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag);

	/**
	 * Return all tags.
	 *
	 * @return array
	 */
	public function tags();

}
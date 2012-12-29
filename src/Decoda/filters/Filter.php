<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;

/**
 * Defines the methods for all Filters to implement.
 *
 * @package	mjohnson.decoda.filters
 */
interface Filter {

	/**
	 * Return the Decoda parser.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 */
	public function getParser();

	/**
	 * Return a message string from the parser.
	 *
	 * @access public
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array());

	/**
	 * Parse the node and its content into an HTML tag.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content);

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $parser
	 * @return \mjohnson\decoda\filters\Filter
	 * @chainable
	 */
	public function setParser(Decoda $parser);

	/**
	 * Add any hook dependencies.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $decoda
	 * @return \mjohnson\decoda\filters\Filter
	 * @chainable
	 */
	public function setupHooks(Decoda $decoda);

	/**
	 * Strip a node and remove content dependent on settings.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function strip(array $tag, $content);

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag);

	/**
	 * Return all tags.
	 *
	 * @access public
	 * @return array
	 */
	public function tags();

}
<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

/**
 * Defines the methods for all Filters to implement.
 */
interface Filter extends Component {

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @param string $tag
	 * @return array
	 */
	public function getTag($tag);

	/**
	 * Return all tags.
	 *
	 * @return array
	 */
	public function getTags();

	/**
	 * Parse the node and its content into an HTML tag.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content);

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

}
<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Filter;

/**
 * This interface represents the rendering engine for tags that use a template.
 * It contains the path were the templates are located and the logic to render these templates.
 */
interface Engine {

	/**
	 * Return the current filter.
	 *
	 * @return \Decoda\Filter
	 */
	public function getFilter();

	/**
	 * Returns the path of the tag templates.
	 *
	 * @return string
	 */
	public function getPath();

	/**
	 * Renders the tag by using the defined templates.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function render(array $tag, $content);

	/**
	 * Sets the current used filter.
	 *
	 * @param \Decoda\Filter $filter
	 * @return \Decoda\Engine
	 */
	public function setFilter(Filter $filter);

	/**
	 * Sets the path to the tag templates.
	 *
	 * @param string $path
	 * @return \Decoda\Engine
	 */
	public function setPath($path);

}

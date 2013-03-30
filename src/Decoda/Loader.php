<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

/**
 * Defines the methods for all resource Loaders to implement.
 */
interface Loader extends Component {

	/**
	 * Load the resources contents.
	 *
	 * @return array
	 */
	public function load();

}
<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Loader;

/**
 * A resource loader that returns data passed directly through the constructor.
 */
class DataLoader extends AbstractLoader {

	/**
	 * Raw data.
	 *
	 * @var mixed
	 */
	protected $_data;

	/**
	 * Store the data directly for later use.
	 *
	 * @param mixed $data
	 */
	public function __construct($data) {
		$this->_data = $data;
	}

	/**
	 * Load the data.
	 *
	 * @return array
	 */
	public function load() {
		return (array) $this->_data;
	}

}
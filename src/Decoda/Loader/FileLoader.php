<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Loader;

use \UnexpectedValueException;
use \Exception;

/**
 * A resource loader for files on the local system.
 */
class FileLoader extends AbstractLoader {

	/**
	 * Path to file.
	 *
	 * @var string
	 */
	protected $_path;

	/**
	 * Store the path and validate the files existence.
	 *
	 * @param string $path
	 * @throws \Exception
	 */
	public function __construct($path) {
		if (!file_exists($path)) {
			throw new Exception(sprintf('File %s does not exist', $path));
		}

		$this->_path = $path;
	}

	/**
	 * Read the resources contents.
	 *
	 * @return array
	 * @throws \UnexpectedValueException
	 */
	public function read() {
		$ext = mb_strtolower(pathinfo($this->_path, PATHINFO_EXTENSION));

		switch ($ext) {
			case 'php':
				return include $this->_path;
			break;
			case 'json':
				return json_decode(file_get_contents($this->_path), true);
			break;
			case 'ini':
				return parse_ini_file($this->_path, true);
			break;
			case 'txt':
				return file($this->_path, FILE_IGNORE_NEW_LINES);
			break;
		}

		throw new UnexpectedValueException(sprintf('Unsupported FileLoader file type %s', $ext));
	}

}
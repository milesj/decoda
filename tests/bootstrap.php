<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

error_reporting(E_ALL | E_STRICT);

// Set constants
define('TEST_DIR', __DIR__);
define('VENDOR_DIR', __DIR__ . '/../../../../vendor');
define('DECODA', dirname(TEST_DIR) . DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Decoda/');

// Ensure that composer has installed all dependencies
if (!file_exists(VENDOR_DIR . '/autoload.php')) {
	exit('Please install composer before running tests!');
}

// Include the composer autoloader
$loader = require VENDOR_DIR . '/autoload.php';
$loader->add('Decoda', TEST_DIR);
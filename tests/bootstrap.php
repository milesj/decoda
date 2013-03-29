<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

error_reporting(E_ALL | E_STRICT);

// Set constants
define('TEST_DIR', __DIR__);
define('DECODA', str_replace('\\', '/', dirname(TEST_DIR) . '/src/Decoda/'));

// Ensure that composer has installed all dependencies
$searchpaths = array(
	__DIR__ . '/../vendor',
	__DIR__ . '/../../../../vendor'
);
foreach ($searchpaths as $searchpath) {
	if (file_exists($searchpath . '/autoload.php')) {
		$autoload = $searchpath . '/autoload.php';
		break;
	}
}

if (empty($autoload)) {
	exit('Please install Composer in Decoda\'s root folder before running tests!');
}

// Include the composer autoloader
$loader = require $autoload;
$loader->add('Decoda', TEST_DIR);

<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

error_reporting(E_ALL | E_STRICT);

// Set constants
define('TEST_DIR', __DIR__);
define('VENDOR_DIR', dirname(TEST_DIR) . '/vendor');
define('DECODA', str_replace('\\', '/', dirname(TEST_DIR) . '/src/Decoda/'));

// Ensure that composer has installed all dependencies
if (!file_exists(VENDOR_DIR . '/autoload.php')) {
    exit('Please install Composer in Decoda\'s root folder before running tests!');
}

// Include the composer autoloader
$loader = require VENDOR_DIR . '/autoload.php';
$loader->add('Decoda', TEST_DIR);

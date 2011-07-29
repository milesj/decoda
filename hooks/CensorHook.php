<?php

class CensorHook extends DecodaHook {
	
    /**
     * List of words to censor.
     *
     * @access protected
     * @var array
     */
    protected $_censored = array();
	
    /**
     * Load the censored words from the text file.
     *
     * @access public
     * @return void
     */
    public function __construct() {
		$path = DECODA_CONFIG .'censored.txt';

		if (file_exists($path)) {
			$this->_censored = array_map('trim', file($path));
		}
    }
	
}

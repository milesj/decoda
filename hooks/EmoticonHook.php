<?php

class EmoticonHook extends DecodaHook {
	
    /**
     * Mapping of emoticons and smilies.
     *
     * @access protected
     * @var array
     */
    protected $_emoticons = array();
	
    /**
     * Load the emoticons from the JSON file.
     *
     * @access public
     * @return void
     */
    public function __construct() {
		$path = DECODA_CONFIG .'emoticons.json';

		if (file_exists($path)) {
			$this->_emoticons = json_decode(file_get_contents($path), true);
		}
    }
	
}

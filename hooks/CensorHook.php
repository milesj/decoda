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
	
	/**
	 * Parse the content by censoring blacklisted words.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterParse($content) {
		if (!empty($this->_censored)) {
			foreach ($this->_censored as $word) {
				$content = preg_replace_callback('/(\s)?'. preg_quote(trim($word), '/') .'(\s)?/is', array($this, '_callback'), $content);
			}
		}

		return $content;
	}
	
	/**
	 * Censor a word if its only by itself.
	 * 
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function _callback($matches) {
		if (count($matches) == 1) {
			return $matches[0];
		}

		$length = mb_strlen(trim($matches[0]));
		$censored = '';
		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';

		for ($i = 1; $i <= $length; ++$i) {
			$censored .= '*';
		}

		return $l . $censored . $r;
	}
	
}

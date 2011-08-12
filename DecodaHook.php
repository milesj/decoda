<?php

abstract class DecodaHook extends DecodaAbstract {
	
	/**
	 * Parse the given content before the primary parse.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return $content;
	}
	
	/**
	 * Parse the given content after the primary parse.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterParse($content) {
		return $content;
	}
	
}
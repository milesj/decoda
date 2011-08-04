<?php

abstract class DecodaHook extends DecodaAbstract {

	/**
	 * Parse the given content.
	 * 
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function parse($content) {
		return $content;
	}
	
}
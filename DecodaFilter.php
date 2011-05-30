<?php

abstract class DecodaFilter {

	protected $_tags = array();
	
	public function tag($tag) {
		return isset($this->_tags[$tag]) ? $this->_tags[$tag] : null;
	}
	
	public function tags() {
		return $this->_tags;
	}

	public function parse($text) {
		return $text;
	}
	
	public function openTag($tag, array $attributes = array()) {
		if (empty($this->_tags[$tag])) {
			return;
		}
		
		$setup = $this->_tags[$tag];
		$attr = '';
		
		if (!empty($attributes)) {
			if (isset($setup['format'])) {
				$attr = ' '. $setup['format'];

				foreach ($attributes as $key => $value) {
					$attr = str_replace('{'. $key .'}', $value, $attr);
				}
			} else {
				foreach ($attributes as $key => $value) {
					$attr .= ' '. $key .'="'. htmlentities($value, ENT_QUOTES, 'UTF-8') .'"';
				}
			}
		}
		
		return '<'. $setup['tag'] . $attr .'>';
	}
	
	public function closeTag($tag) {
		if (empty($this->_tags[$tag])) {
			return;
		}
		
		return '</'. $this->_tags[$tag]['tag'] .'>';
	}
	
}
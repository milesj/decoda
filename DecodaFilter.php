<?php

abstract class DecodaFilter {

	protected $_tags = array();

	public function tag($tag) {
		return isset($this->_tags[$tag]) ? $this->_tags[$tag] : null;
	}
	
	public function tags() {
		return $this->_tags;
	}

	public function parse($tag, $content) {
		$parsed  = $this->openTag($tag['tag'], $tag['attributes']);
		$parsed .= $content;
		$parsed .= $this->closeTag($tag['tag']);
		
		return $parsed;
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
					if (isset($setup['map'][$key])) {
						$key = $setup['map'][$key];
					}
					
					$attr .= ' '. $key .'="'. $value .'"';
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
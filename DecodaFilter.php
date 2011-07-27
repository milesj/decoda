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
		if (empty($this->_tags[$tag['tag']])) {
			return;
		}
		
		$attributes = $tag['attributes'];
		$setup = $this->_tags[$tag['tag']];
		$attr = '';
		
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
		
		$parsed = '<'. $setup['tag'] . $attr;
		
		if (empty($setup['selfClose'])) {
			$parsed .= '>'. $content .'</'. $setup['tag'] .'>';
		} else {
			$parsed .= '/>';
		}
		
		return $parsed;
	}
	
}
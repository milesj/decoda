<?php

abstract class DecodaFilter {
	
	/**
	 * Type constants.
	 */
	const TYPE_NONE = 0;
	const TYPE_INLINE = 1;
	const TYPE_BLOCK = 2;
	const TYPE_BOTH = 3;

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array();
	
	/**
	 * Default tag configurations.
	 * 
	 * @access private
	 * @var array
	 */
	private $__defaults = array(
		'tag' => '',
		'template' => '',
		'type' => self::TYPE_BLOCK,
		'allowed' => self::TYPE_BOTH,
		'lineBreaks' => true,
		'selfClose' => false,
		'parent' => array(),
		'attributes' => array(),
		'map' => array(),
		'format' => ''
	);

	/**
	 * Return a tag if it exists, and merge with defaults.
	 * 
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		if (isset($this->_tags[$tag])) {
			return $this->_tags[$tag] + $this->__defaults;
		}
		
		return null;
	}
	
	/**
	 * Return all tags.
	 * 
	 * @access public
	 * @return array
	 */
	public function tags() {
		return $this->_tags;
	}

	/**
	 * Parse the node and its content into an HTML tag.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string 
	 */
	public function parse(array $tag, $content) {
		if (empty($this->_tags[$tag['tag']])) {
			return;
		}
		
		$attributes = $tag['attributes'];
		$setup = $this->tag($tag['tag']);
		$attr = '';
		
		if (!empty($setup['format'])) {
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
		
		if ($setup['selfClose']) {
			$parsed .= '/>';
		} else {
			$parsed .= '>'. $content .'</'. $setup['tag'] .'>';
		}
		
		return $parsed;
	}
	
}
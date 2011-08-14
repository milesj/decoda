<?php

abstract class DecodaFilter extends DecodaAbstract {
	
	/**
	 * Type constants.
	 */
	const TYPE_NONE_PRESERVE = -1;
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
	 * Return a tag if it exists, and merge with defaults.
	 * 
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		$defaults = array(
			'key' => $tag,
			'tag' => '',
			'template' => '',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'lineBreaks' => true,
			'selfClose' => false,
			'parent' => array(),
			'children' => array(),
			'attributes' => array(),
			'map' => array(),
			'format' => '',
			'pattern' => ''
		);
		
		if (isset($this->_tags[$tag])) {
			return $this->_tags[$tag] + $defaults;
		}
		
		return $defaults;
	}
	
	/**
	 * Return a message string from the parser.
	 * 
	 * @access public
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array()) {
		return $this->getParser()->message($key, $vars);
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
		$setup = $this->tag($tag['tag']);
		
		if (empty($setup)) {
			return;
		} else if (!empty($setup['template'])) {
			return $this->_render($tag, $content);
		}		
		
		$attributes = $tag['attributes'];
		$xhtml = $this->getParser()->config('xhtml');
		$attr = '';
		$tag = $setup['tag'];
		
		if (is_array($tag)) {
			$tag = $tag[$xhtml];
		}
		
		if ($setup['lineBreaks']) {
			$content = nl2br($content, $xhtml);
		}

		// If content doesn't match the pattern, don't wrap in a tag
		if (empty($attributes['default']) && !empty($setup['pattern'])) {
			if (!preg_match($setup['pattern'], $content)) {
				return $content;
			}
			
			$attributes['default'] = $content;
		}
		
		// Format attributes
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
		
		$parsed = '<'. $tag . $attr;
		
		if ($setup['selfClose']) {
			$parsed .= $xhtml ? '/>' : '>';
		} else {
			$parsed .= '>'. $content .'</'. $tag .'>';
		}
		
		return $parsed;
	}
	
	/**
	 * Render the tag using a template.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string 
	 */
	protected function _render(array $tag, $content) {
		$setup = $this->tag($tag['tag']);
		$path = DECODA_TEMPLATES . $setup['template'] .'.php';
		
		if (!file_exists($path)) {
			throw new Exception(sprintf('Template file %s does not exist.', $setup['template']));
		}
		
		$vars = array();
		
		foreach ($tag['attributes'] as $key => $value) {
			if (isset($setup['map'][$key])) {
				$key = $setup['map'][$key];
			}

			$vars[$key] = $value;
		}
		
		extract($vars, EXTR_SKIP);
		ob_start();

		include $path;

		return ob_get_clean();
	}
	
}
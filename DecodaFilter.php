<?php

abstract class DecodaFilter extends DecodaAbstract {

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
			'pattern' => '',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'lineBreaks' => true,
			'autoClose' => false,
			'preserve' => false,
			'escape' => false,
			'depth' => -1,
			'parent' => array(),
			'children' => array(),
			'attributes' => array(),
			'map' => array(),
			'html' => array()
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
		$xhtml = $this->getParser()->config('xhtml');

		if (empty($setup)) {
			return;
		}

		// If content doesn't match the pattern, don't wrap in a tag
		if (!empty($setup['pattern'])) {
			$test = !empty($tag['attributes']['default']) ? $tag['attributes']['default'] : $content;

			if (!preg_match($setup['pattern'], $test)) {
				return $content;
			}
		}
		
		// Add linebreaks
		if ($setup['lineBreaks']) {
			$content = nl2br($content, $xhtml);
		}

		// Escape entities
		if ($setup['escape']) {
			$content = htmlentities($content, ENT_QUOTES, 'UTF-8');
		}

		// Use a template if it exists
		if (!empty($setup['template'])) {
			return $this->_render($tag, $content);
		}

		// Format attributes
		$attributes = array();
		$attr = '';
		
		if (!empty($tag['attributes'])) {
			foreach ($tag['attributes'] as $key => $value) {
				if (isset($setup['map'][$key])) {
					$key = $setup['map'][$key];
				}

				if ($key == 'default') {
					continue;
				}
				
				$attributes[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
			}
		}
		
		if (!empty($setup['html'])) {
			$attributes += $setup['html'];
		}
		
		foreach ($attributes as $key => $value) {
			$attr .= ' '. $key .'="'. $value .'"';
		}

		// Build HTML tag
		$html = $setup['tag'];

		if (is_array($html)) {
			$html = $html[$xhtml];
		}

		$parsed = '<'. $html . $attr;

		if ($setup['autoClose']) {
			$parsed .= $xhtml ? '/>' : '>';
		} else {
			$parsed .= '>'. (!empty($tag['content']) ? $tag['content'] : $content) .'</'. $html .'>';
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

		if ($setup['lineBreaks']) {
			return str_replace(array("\n", "\r"), "", ob_get_clean());
		}

		return ob_get_clean();
	}

}
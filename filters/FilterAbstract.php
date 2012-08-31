<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\FilterInterface;

/**
 * A filter defines the list of tags and its associative markup to parse out of a string.
 * Supports a wide range of parameters to customize the output of each tag.
 *
 * @package	mjohnson.decoda.filters
 * @abstract
 */
abstract class FilterAbstract implements FilterInterface {

	/**
	 * Type constants.
	 *
	 * 	TYPE_NONE	- Will not accept block or inline (for validating)
	 * 	TYPE_INLINE	- Inline element that can only contain child inlines
	 * 	TYPE_BLOCK	- Block element that can contain both inline and block
	 * 	TYPE_BOTH	- Will accept either type (for validating)
	 */
	const TYPE_NONE = 0;
	const TYPE_INLINE = 1;
	const TYPE_BLOCK = 2;
	const TYPE_BOTH = 3;

	/**
	 * Newline and carriage return formatting.
	 *
	 * 	NL_REMOVE	- Will be removed
	 * 	NL_PRESERVE	- Will be preserved as \n and \r
	 * 	NL_CONVERT	- Will be converted to <br> tags
	 */
	const NL_REMOVE = 0;
	const NL_PRESERVE = 1;
	const NL_CONVERT = 2;

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array();

	/**
	 * Default tag configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_defaults = array(
		/**
		 * tag				- (string) Decoda tag
		 * htmlTag			- (string) HTML replacement tag
		 * template			- (string) Template file to use for rendering
		 * displayType		- (constant) Type of HTML element: block or inline
		 * allowedTypes		- (constant) What types of elements are allowed to be nested
		 */
		'tag' => '',
		'htmlTag' => '',
		'template' => '',
		'displayType' => self::TYPE_BLOCK,
		'allowedTypes' => self::TYPE_BOTH,

		/**
		 * attributes		- (array) Custom attributes to parse out of the Decoda tag
		 * mapAttributes	- (array) Map parsed and custom attributes to different names
		 * htmlAttributes	- (array) Custom HTML attributes to append to the parsed tag
		 * escapeAttributes	- (boolean) Escape HTML entities within the parsed attributes
		 */
		'attributes' => array(),
		'mapAttributes' => array(),
		'htmlAttributes' => array(),
		'escapeAttributes' => true,

		/**
		 * lineBreaks		- (boolean) Convert line breaks within the content body
		 * autoClose		- (boolean) HTML tag is self closing
		 * preserveTags		- (boolean) Will not convert nested Decoda markup within this tag
		 * contentPattern	- (string) Regex pattern that the content or default attribute must pass
		 * testNoDefault	- (boolean) Will only test the pattern on the content if the default attribute doesn't exist
		 */
		'lineBreaks' => self::NL_CONVERT,
		'autoClose' => false,
		'preserveTags' => false,
		'contentPattern' => '',
		'testNoDefault' => false,

		/**
		 * parent				- (array) List of Decoda keys that this tag can only be a direct child of
		 * childrenWhitelist	- (array) List of Decoda keys that can only be a direct descendant
		 * childrenBlacklist	- (array) List of Decoda keys that can not be a direct descendant
		 * maxChildDepth		- (integer) Max depth for nested children of the same tag (-1 to disable)
		 */
		'parent' => array(),
		'childrenWhitelist' => array(),
		'childrenBlacklist' => array(),
		'maxChildDepth' => -1,
	);

	/**
	 * Decoda object.
	 *
	 * @access protected
	 * @var \mjohnson\decoda\Decoda
	 */
	protected $_parser;

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array();

	/**
	 * Apply configuration.
	 *
	 * @access public
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		$this->_config = $config + $this->_config;
	}

	/**
	 * Return a specific configuration key value.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public function config($key) {
		return isset($this->_config[$key]) ? $this->_config[$key] : null;
	}

	/**
	 * Return the Decoda parser.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 */
	public function getParser() {
		return $this->_parser;
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

		if (!$setup) {
			return null;
		}

		// If content doesn't match the pattern, don't wrap in a tag
		if ($setup['contentPattern']) {
			if ($setup['testNoDefault']) {
				if (!isset($tag['attributes']['default']) && !preg_match($setup['contentPattern'], $content)) {
					return sprintf('(Invalid %s)', $tag['tag']);
				}
			} else if (!preg_match($setup['contentPattern'], $content)) {
				return sprintf('(Invalid %s)', $tag['tag']);
			}
		}

		// Add line breaks
		switch ($setup['lineBreaks']) {
			case self::NL_REMOVE:
				$content = str_replace(array("\n", "\r"), "", $content);
			break;
			case self::NL_CONVERT:
				$content = nl2br($content, $xhtml);
			break;
		}

		// Format attributes
		$attributes = (array) $setup['htmlAttributes'];
		$attr = '';

		if ($tag['attributes']) {
			foreach ($tag['attributes'] as $key => $value) {
				if ($key === 'default' || substr($value, 0, 11) === 'javascript:') {
					continue;
				}

				if ($setup['escapeAttributes']) {
					$attributes[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
				} else {
					$attributes[$key] = $value;
				}
			}
		}

		foreach ($attributes as $key => $value) {
			$attr .= ' ' . $key . '="' . $value . '"';
		}

		// Use a template if it exists
		if ($setup['template']) {
			$tag['attributes'] = $attributes;

			$engine = $this->getParser()->getEngine();
			$engine->setFilter($this);

			$parsed = $engine->render($tag, $content);

			if ($setup['lineBreaks'] !== self::NL_PRESERVE) {
				$parsed = str_replace(array("\n", "\r"), "", $parsed);
			}

			return $parsed;
		}

		// Build HTML tag
		$html = $setup['htmlTag'];

		if (is_array($html)) {
			$html = $html[$xhtml];
		}

		if ($setup['autoClose']) {
			$parsed = '<' . $html . $attr . ($xhtml ? ' /' : '') . '>';
		} else {
			$parsed = '<' . $html . $attr . '>' . (!empty($tag['content']) ? $tag['content'] : $content) . '</' . $html . '>';
		}

		return $parsed;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $parser
	 * @return \mjohnson\decoda\filters\FilterAbstract
	 * @chainable
	 */
	public function setParser(Decoda $parser) {
		$this->_parser = $parser;

		return $this;
	}

	/**
	 * Add any hook dependencies.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $decoda
	 * @return \mjohnson\decoda\filters\FilterAbstract
	 * @chainable
	 */
	public function setupHooks(Decoda $decoda) {
		return $this;
	}

	/**
	 * Return a tag if it exists, and merge with defaults.
	 *
	 * @access public
	 * @param string $tag
	 * @return array
	 */
	public function tag($tag) {
		$defaults = $this->_defaults;
		$defaults['tag'] = $tag;

		if (isset($this->_tags[$tag])) {
			return $this->_tags[$tag] + $defaults;
		}

		return $defaults;
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

}
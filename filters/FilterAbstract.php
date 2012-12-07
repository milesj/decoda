<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\Filter;

/**
 * A filter defines the list of tags and its associative markup to parse out of a string.
 * Supports a wide range of parameters to customize the output of each tag.
 *
 * @package	mjohnson.decoda.filters
 * @abstract
 */
abstract class FilterAbstract implements Filter {

	/**
	 * Regex patterns for attribute parsing.
	 */
	const WILDCARD = '/(.*?)/';
	const ALPHA = '/^[a-z_\-\s]+$/i';
	const ALNUM = '/^[a-z0-9,_\s\.\-\+\/]+$/i';
	const NUMERIC = '/^[0-9,\.\-\+\/]+$/';

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
		'displayType' => Decoda::TYPE_BLOCK,
		'allowedTypes' => Decoda::TYPE_BOTH,

		/**
		 * attributes		- (array) Custom attributes to parse out of the Decoda tag
		 * mapAttributes	- (array) Map parsed and custom attributes to different names, as well as aliasing attributes
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
		 */
		'lineBreaks' => Decoda::NL_CONVERT,
		'autoClose' => false,
		'preserveTags' => false,

		/**
		 * contentPattern	- (string) Regex pattern that the content or default attribute must pass
		 * stripContent		- (boolean) Should content within tags be removed when stripping tags
		 */
		'contentPattern' => '',
		'stripContent' => false,

		/**
		 * parent				- (array) List of Decoda tags that this tag can only be a direct child of
		 * childrenWhitelist	- (array) List of Decoda tags that can only be a direct descendant
		 * childrenBlacklist	- (array) List of Decoda tags that can not be a direct descendant
		 * maxChildDepth		- (integer) Max depth for nested children of the same tag (-1 to disable)
		 * persistContent		- (boolean) Should we persist text content from within deeply nested tags (but remove their wrapping tags)
		 */
		'parent' => array(),
		'childrenWhitelist' => array(),
		'childrenBlacklist' => array(),
		'maxChildDepth' => -1,
		'persistContent' => true
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
		$xhtml = $this->getParser()->config('xhtmlOutput');

		if (!$setup) {
			return null;
		}

		// Merge arguments with method of same tag name
		// If the method returns false, exit early
		if (method_exists($this, $tag['tag'])) {
			if ($response = call_user_func_array(array($this, $tag['tag']), array($tag, $content))) {
				list($tag, $content) = $response;
			} else {
				return null;
			}
		}

		if ($content) {
			// If content doesn't match the pattern, don't wrap in a tag
			if ($setup['contentPattern']) {
				if (!preg_match($setup['contentPattern'], $content)) {
					return sprintf('(Invalid %s)', $tag['tag']);
				}
			}

			// Process line breaks
			switch ($setup['lineBreaks']) {
				case Decoda::NL_CONVERT:
					$content = nl2br($content, $xhtml);
				// Fall-through
				case Decoda::NL_REMOVE:
					$content = str_replace("\n", "", $content);
				break;
			}
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
			$tag['attributes'] = $attributes + $this->_config;

			$engine = $this->getParser()->getEngine();
			$engine->setFilter($this);

			$parsed = $engine->render($tag, $content);

			if ($setup['lineBreaks'] !== Decoda::NL_PRESERVE) {
				$parsed = str_replace(array("\r", "\n"), "", $parsed);

			// Normalize
			} else {
				$parsed = str_replace("\r\n", "\n", $parsed);
				$parsed = str_replace("\r", "\n", $parsed);
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
	 * @return \mjohnson\decoda\filters\Filter
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
	 * @return \mjohnson\decoda\filters\Filter
	 * @chainable
	 */
	public function setupHooks(Decoda $decoda) {
		return $this;
	}

	/**
	 * Strip a node and remove content dependent on settings.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function strip(array $tag, $content) {
		$setup = $this->tag($tag['tag']);

		if (!$setup || $setup['stripContent']) {
			return '';
		}

		return $content;
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
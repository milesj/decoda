<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda;

use \OutOfRangeException;
use \InvalidArgumentException;

/**
 * A lightweight lexical string parser for simple markup syntax.
 * Provides a very powerful filter and hook system to extend the parsing cycle.
 */
class Decoda {

	/**
	 * Tag type constants.
	 */
	const TAG_NONE = 0;
	const TAG_OPEN = 1;
	const TAG_CLOSE = 2;
	const TAG_SELF_CLOSE = 3;

	/**
	 * Error type constants.
	 */
	const ERROR_ALL = 0;
	const ERROR_NESTING = 1;
	const ERROR_CLOSING = 2;
	const ERROR_SCOPE = 3;

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
	 * 	NL_PRESERVE	- Will be preserved as \n
	 * 	NL_CONVERT	- Will be converted to <br> tags
	 */
	const NL_REMOVE = 0;
	const NL_PRESERVE = 1;
	const NL_CONVERT = 2;

	/**
	 * Blacklist of tags not to parse.
	 *
	 * @var array
	 */
	protected $_blacklist = array();

	/**
	 * Extracted chunks of text and tags.
	 *
	 * @var array
	 */
	protected $_chunks = array();

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'open' => '[',
		'close' => ']',
		'locale' => 'en-us',
		'disabled' => false,
		'shorthandLinks' => false,
		'xhtmlOutput' => false,
		'escapeHtml' => true,
		'strictMode' => true,
		'maxNewlines' => 3
	);

	/**
	 * Logged errors for incorrectly nested nodes and types.
	 *
	 * @var array
	 */
	protected $_errors = array();

	/**
	 * List of all instantiated filter objects.
	 *
	 * @var array
	 */
	protected $_filters = array();

	/**
	 * Mapping of tags to its filter object.
	 *
	 * @var array
	 */
	protected $_filterMap = array();

	/**
	 * List of all instantiated hook objects.
	 *
	 * @var array
	 */
	protected $_hooks = array();

	/**
	 * Message strings for localization purposes.
	 *
	 * @var array
	 */
	protected $_messages = array();

	/**
	 * Children nodes.
	 *
	 * @var array
	 */
	protected $_nodes = array();

	/**
	 * The parsed string.
	 *
	 * @var string
	 */
	protected $_parsed = '';

	/**
	 * Configuration folder paths.
	 *
	 * @var array
	 */
	protected $_paths = array();

	/**
	 * The raw string before parsing.
	 *
	 * @var string
	 */
	protected $_string = '';

	/**
	 * The stripped string.
	 *
	 * @var string
	 */
	protected $_stripped = '';

	/**
	 * List of tags from filters.
	 *
	 * @var array
	 */
	protected $_tags = array();

	/**
	 * Template engine used for parsing.
	 *
	 * @var \Decoda\Engine
	 */
	protected $_engine = null;

	/**
	 * Whitelist of tags to parse.
	 *
	 * @var array
	 */
	protected $_whitelist = array();

	/**
	 * Store the text and single instance configuration.
	 *
	 * @param string $string
	 * @param array $config
	 */
	public function __construct($string = '', array $config = array()) {
		$this->reset($string, true);
		$this->setConfig($config);
		$this->addPath(__DIR__ . '/config/');

		// Set the default engine
		$engine = new \Decoda\Engine\PhpEngine();
		$engine->addPath(__DIR__ . '/templates/');

		$this->setEngine($engine);
	}

	/**
	 * Add additional filters.
	 *
	 * @param \Decoda\Filter $filter
	 * @param string $key
	 * @return \Decoda\Decoda
	 */
	public function addFilter(Filter $filter, $key = null) {
		$filter->setParser($this);

		if (!$key) {
			$key = explode('\\', get_class($filter));
			$key = str_replace('Filter', '', end($key));
		}

		$tags = $filter->getTags();

		$this->_filters[$key] = $filter;
		$this->_tags = $tags + $this->_tags;

		foreach ($tags as $tag => $options) {
			$this->_filterMap[$tag] = $key;
		}

		$filter->setupHooks($this);

		return $this;
	}

	/**
	 * Add hooks that are triggered at specific events.
	 *
	 * @param \Decoda\Hook $hook
	 * @param string $key
	 * @return \Decoda\Decoda
	 */
	public function addHook(Hook $hook, $key = null) {
		$hook->setParser($this);

		if (!$key) {
			$key = explode('\\', get_class($hook));
			$key = str_replace('Hook', '', end($key));
		}

		$this->_hooks[$key] = $hook;

		$hook->setupFilters($this);

		ksort($this->_hooks);

		return $this;
	}

	/**
	 * Add a loader that will generate localization messages.
	 *
	 * @param \Decoda\Loader $loader
	 * @return \Decoda\Decoda
	 */
	public function addMessages(Loader $loader) {
		$loader->setParser($this);

		if ($messages = $loader->load()) {
			foreach ($messages as $locale => $strings) {
				if (!empty($this->_messages[$locale])) {
					$strings = array_merge($this->_messages[$locale], $strings);
				}

				$this->_messages[$locale] = $strings;
			}
		}

		return $this;
	}

	/**
	 * Add a configuration lookup path.
	 *
	 * @param string $path
	 * @return \Decoda\Decoda
	 */
	public function addPath($path) {
		if (substr($path, -1) !== '/') {
			$path .= '/';
		}

		$this->_paths[] = $path;

		return $this;
	}

	/**
	 * Add tags to the blacklist.
	 *
	 * @return \Decoda\Decoda
	 */
	public function blacklist() {
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$args = $args[0];
		}

		$this->_blacklist += $args;
		$this->_blacklist = array_filter($this->_blacklist);

		return $this;
	}

	/**
	 * Apply default filters and hooks if none are set.
	 *
	 * @return \Decoda\Decoda
	 */
	public function defaults() {
		$this->addFilter(new \Decoda\Filter\DefaultFilter());
		$this->addFilter(new \Decoda\Filter\EmailFilter());
		$this->addFilter(new \Decoda\Filter\ImageFilter());
		$this->addFilter(new \Decoda\Filter\UrlFilter());
		$this->addFilter(new \Decoda\Filter\TextFilter());
		$this->addFilter(new \Decoda\Filter\BlockFilter());
		$this->addFilter(new \Decoda\Filter\VideoFilter());
		$this->addFilter(new \Decoda\Filter\CodeFilter());
		$this->addFilter(new \Decoda\Filter\QuoteFilter());
		$this->addFilter(new \Decoda\Filter\ListFilter());

		$this->addHook(new \Decoda\Hook\CensorHook());
		$this->addHook(new \Decoda\Hook\ClickableHook());

		return $this;
	}

	/**
	 * Toggle parsing.
	 *
	 * @param boolean $status
	 * @return \Decoda\Decoda
	 */
	public function disable($status = true) {
		$this->_config['disabled'] = (bool) $status;

		return $this;
	}

	/**
	 * Return the current blacklist.
	 *
	 * @return array
	 */
	public function getBlacklist() {
		return $this->_blacklist;
	}

	/**
	 * Return a specific configuration key value.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getConfig($key) {
		return isset($this->_config[$key]) ? $this->_config[$key] : null;
	}

	/**
	 * Return the parsing errors.
	 *
	 * @param int $type
	 * @return array
	 */
	public function getErrors($type = self::ERROR_ALL) {
		if ($type === self::ERROR_ALL) {
			return $this->_errors;
		}

		$clean = array();

		if ($this->_errors) {
			foreach ($this->_errors as $error) {
				if ($error['type'] === self::ERROR_NESTING) {
					$clean[] = $error;

				} else if ($error['type'] === self::ERROR_CLOSING) {
					$clean[] = $error;

				} else if ($error['type'] === self::ERROR_SCOPE) {
					$clean[] = $error;
				}
			}
		}

		return $clean;
	}

	/**
	 * Return a specific filter based on class name.
	 *
	 * @param string $filter
	 * @return \Decoda\Filter
	 * @throws \OutOfRangeException
	 */
	public function getFilter($filter) {
		if ($this->hasFilter($filter)) {
			return $this->_filters[$filter];
		}

		throw new OutOfRangeException(sprintf('Filter %s does not exist', $filter));
	}

	/**
	 * Return a filter based on its supported tag.
	 *
	 * @param string $tag
	 * @return \Decoda\Filter
	 * @throws \OutOfRangeException
	 */
	public function getFilterByTag($tag) {
		if (isset($this->_filterMap[$tag])){
			return $this->getFilter($this->_filterMap[$tag]);
		}

		throw new OutOfRangeException(sprintf('No filter could be located for tag %s', $tag));
	}

	/**
	 * Return all filters.
	 *
	 * @return array
	 */
	public function getFilters() {
		return $this->_filters;
	}

	/**
	 * Return a specific hook based on class name.
	 *
	 * @param string $hook
	 * @return \Decoda\Hook
	 * @throws \OutOfRangeException
	 */
	public function getHook($hook) {
		if ($this->hasHook($hook)) {
			return $this->_hooks[$hook];
		}

		throw new OutOfRangeException(sprintf('Hook %s does not exist', $hook));
	}

	/**
	 * Return all hooks.
	 *
	 * @return array
	 */
	public function getHooks() {
		return $this->_hooks;
	}

	/**
	 * Returns the current used template engine.
	 * In case no engine is set the default php engine gonna be used.
	 *
	 * @return \Decoda\Engine
	 */
	public function getEngine() {
		return $this->_engine;
	}

	/**
	 * Return the configuration folder paths.
	 *
	 * @return array
	 */
	public function getPaths() {
		return $this->_paths;
	}

	/**
	 * Return the current whitelist.
	 *
	 * @return array
	 */
	public function getWhitelist() {
		return $this->_whitelist;
	}

	/**
	 * Check if a filter exists.
	 *
	 * @param string $filter
	 * @return boolean
	 */
	public function hasFilter($filter) {
		return isset($this->_filters[$filter]);
	}

	/**
	 * Check if a hook exists.
	 *
	 * @param string $hook
	 * @return boolean
	 */
	public function hasHook($hook) {
		return isset($this->_hooks[$hook]);
	}

	/**
	 * Return a message string if it exists.
	 *
	 * @param string $key
	 * @param array $vars
	 * @return string
	 * @throws \OutOfRangeException
	 */
	public function message($key, array $vars = array()) {
		if (!$this->_messages) {
			$this->_loadMessages();
		}

		$locale = $this->getConfig('locale');

		if (empty($this->_messages[$locale])) {
			throw new OutOfRangeException(sprintf('Localized messages for %s do not exist', $locale));
		}

		$string = isset($this->_messages[$locale][$key]) ? $this->_messages[$locale][$key] : '';

		if ($string && $vars) {
			foreach ($vars as $key => $value) {
				$string = str_replace('{' . $key . '}', $value, $string);
			}
		}

		return $string;
	}

	/**
	 * Parse the node list by looping through each one, validating, applying filters, building and finally concatenating the string.
	 *
	 * @param boolean $echo
	 * @return string
	 */
	public function parse($echo = false) {
		if ($this->_parsed) {
			if ($echo) {
				echo $this->_parsed;
			}

			return $this->_parsed;
		}

		$this->_triggerHook('startup');

		$string = $this->_triggerHook('beforeParse', $this->_string);

		if ($this->_isParseable($string)) {
			$string = $this->_parse($this->_extractChunks($string));
		} else {
			$string = nl2br($string, $this->getConfig('xhtmlOutput'));
		}

		$string = $this->_triggerHook('afterParse', $string);

		$this->_parsed = $this->_cleanNewlines($string);

		if ($echo) {
			echo $this->_parsed;
		}

		return $this->_parsed;
	}

	/**
	 * Remove filter(s).
	 *
	 * @param string|array $filters
	 * @return \Decoda\Decoda
	 */
	public function removeFilter($filters) {
		foreach ((array) $filters as $filter) {
			unset($this->_filters[$filter]);

			foreach ($this->_filterMap as $tag => $f) {
				if ($f === $filter) {
					unset($this->_filterMap[$tag]);
				}
			}
		}

		return $this;
	}

	/**
	 * Remove hook(s).
	 *
	 * @param string|array $hooks
	 * @return \Decoda\Decoda
	 */
	public function removeHook($hooks) {
		foreach ((array) $hooks as $hook) {
			unset($this->_hooks[$hook]);
		}

		return $this;
	}

	/**
	 * Reset the parser to a new string.
	 *
	 * @param string $string
	 * @param boolean $flush
	 * @return \Decoda\Decoda
	 */
	public function reset($string, $flush = false) {
		$this->_chunks = array();
		$this->_nodes = array();
		$this->_blacklist = array();
		$this->_whitelist = array();
		$this->_parsed = '';
		$this->_stripped = '';
		$this->_string = $this->_escape($string);

		if ($flush) {
			$this->resetFilters();
			$this->resetHooks();
			$this->_paths = array();
		}

		return $this;
	}

	/**
	 * Reset all filters.
	 *
	 * @return \Decoda\Decoda
	 */
	public function resetFilters() {
		$this->_filters = array();
		$this->_filterMap = array();
		$this->_tags = array();

		$this->addFilter(new \Decoda\Filter\EmptyFilter());

		return $this;
	}

	/**
	 * Reset all hooks.
	 *
	 * @return \Decoda\Decoda
	 */
	public function resetHooks() {
		$this->_hooks = array();

		$this->addHook(new \Decoda\Hook\EmptyHook());

		return $this;
	}

	/**
	 * Apply multiple configurations at once.
	 *
	 * @param array $config
	 * @return \Decoda\Decoda
	 */
	public function setConfig(array $config = array()) {
		if (!$config) {
			return $this;
		}

		foreach ($config as $key => $value) {
			switch ($key) {
				case 'open':
				case 'close':
					$this->setBrackets($config['open'], $config['close']);
				break;
				case 'locale':
					$this->setLocale($value);
				break;
				case 'disabled':
					$this->disable($value);
				break;
				case 'shorthand':
				case 'shorthandLinks':
					$this->setShorthand($value);
				break;
				case 'xhtml':
				case 'xhtmlOutput':
					$this->setXhtml($value);
				break;
				case 'escape':
				case 'escapeHtml':
					$this->setEscaping($value);
				break;
				case 'strict':
				case 'strictMode':
					$this->setStrict($value);
				break;
				case 'newlines':
				case 'maxNewlines':
					$this->setMaxNewlines($value);
				break;
			}
		}

		return $this;
	}

	/**
	 * Change the open/close markup brackets.
	 *
	 * @param string $open
	 * @param string $close
	 * @return \Decoda\Decoda
	 * @throws \InvalidArgumentException
	 */
	public function setBrackets($open, $close) {
		if (!$open || !$close) {
			throw new InvalidArgumentException('Both the open and close brackets are required');
		}

		$this->_config['open'] = (string) $open;
		$this->_config['close'] = (string) $close;

		return $this;
	}

	/**
	 * Toggle XSS escaping.
	 *
	 * @param boolean $status
	 * @return \Decoda\Decoda
	 */
	public function setEscaping($status = true) {
		$this->_config['escapeHtml'] = (bool) $status;

		return $this;
	}

	/**
	 * Set the locale.
	 *
	 * @param string $locale
	 * @return \Decoda\Decoda
	 */
	public function setLocale($locale) {
		$this->_config['locale'] = $locale;

		return $this;
	}

	/**
	 * Set the max amount of newlines.
	 *
	 * @param boolean $max
	 * @return \Decoda\Decoda
	 */
	public function setMaxNewlines($max) {
		$this->_config['maxNewlines'] = (int) $max;

		return $this;
	}

	/**
	 * Toggle shorthand syntax.
	 *
	 * @param boolean $status
	 * @return \Decoda\Decoda
	 */
	public function setShorthand($status = true) {
		$this->_config['shorthandLinks'] = (bool) $status;

		return $this;
	}

	/**
	 * Toggle strict parsing.
	 *
	 * @param boolean $strict
	 * @return \Decoda\Decoda
	 */
	public function setStrict($strict = true) {
		$this->_config['strictMode'] = (bool) $strict;

		return $this;
	}

	/**
	 * Sets the template engine which gonna be used for all tags with templates.
	 *
	 * @param \Decoda\Engine $engine
	 * @return \Decoda\Decoda
	 */
	public function setEngine(Engine $engine) {
		$engine->setParser($this);

		$this->_engine = $engine;

		return $this;
	}

	/**
	 * Toggle XHTML.
	 *
	 * @param boolean $status
	 * @return \Decoda\Decoda
	 */
	public function setXhtml($status = true) {
		$this->_config['xhtmlOutput'] = (bool) $status;

		return $this;
	}

	/**
	 * Strip the node list by looping through all the nodes and stripping out tags and content.
	 *
	 * @param boolean $html
	 * @param boolean $echo
	 * @return string
	 */
	public function strip($html = false, $echo = false) {
		if ($this->_stripped) {
			if ($echo) {
				echo $this->_stripped;
			}

			return $this->_stripped;
		}

		$this->_triggerHook('startup');

		$string = $this->_triggerHook('beforeStrip', $this->_string);

		if ($this->_isParseable($string)) {
			$string = $this->_strip($this->_extractChunks($string));
		} else {
			$string = nl2br($string, $this->getConfig('xhtmlOutput'));
		}

		$string = $this->_triggerHook('afterStrip', $string);

		if (!$html) {
			$string = strip_tags($string);
		}

		$this->_stripped = $this->_cleanNewlines($string);

		if ($echo) {
			echo $this->_stripped;
		}

		return $this->_stripped;
	}

	/**
	 * Add tags to the whitelist.
	 *
	 * @return \Decoda\Decoda
	 */
	public function whitelist() {
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$args = $args[0];
		}

		$this->_whitelist += $args;
		$this->_whitelist = array_filter($this->_whitelist);

		return $this;
	}

	/**
	 * Determine if the string is an open or closing tag. If so, parse out the attributes.
	 *
	 * @param string $string
	 * @return array
	 */
	protected function _buildTag($string) {
		$disabled = $this->getConfig('disabled');
		$oe = $this->getConfig('open');
		$ce = $this->getConfig('close');
		$tag = null;
		$type = self::TAG_NONE;
		$attributes = array();

		// Closing tag
		if (mb_substr($string, 0, 2) === $oe . '/') {
			$tag = trim(mb_substr($string, 2, mb_strlen($string) - 3));
			$type = self::TAG_CLOSE;

		// Opening tag
		} else if (preg_match('/' . preg_quote($oe, '/') . '([-a-z0-9]+)(.*?)' . preg_quote($ce, '/') . '/i', $string, $matches)) {
			$tag = trim($matches[1]);
			$type = self::TAG_OPEN;
		}

		// Check for lowercase tag in case they uppercased it: IMG, B, etc
		if (isset($this->_tags[mb_strtolower($tag)])) {
			$tag = mb_strtolower($tag);
		}

		if (!isset($this->_tags[$tag])) {
			return false;
		}

		// Check if is a self closing tag
		if (self::TAG_OPEN === $type) {
			if (
				isset($this->_tags[$tag]['autoClose']) &&
				$this->_tags[$tag]['autoClose'] &&
				mb_substr($string, -2) === '/' . $ce
			) {
				$type = self::TAG_SELF_CLOSE;
			}
		}

		// Find attributes
		if (!$disabled) {
			$found = array();

			preg_match_all('/([a-z]+)=\"(.*?)\"/i', $string, $matches, PREG_SET_ORDER);

			if ($matches) {
				foreach ($matches as $match) {
					$found[$match[1]] = $match[2];
				}
			}

			// Find attributes that aren't surrounded by quotes
			if (!$this->getConfig('strictMode')) {
				preg_match_all('/([a-z]+)=([^\s' . preg_quote($ce, '/') . ']+)/i', $string, $matches, PREG_SET_ORDER);

				if ($matches) {
					foreach ($matches as $match) {
						if (!isset($found[$match[1]])) {
							$found[$match[1]] = $match[2];
						}
					}
				}
			}

			if ($found) {
				$source = $this->_tags[$tag];

				foreach ($found as $key => $value) {
					$key = mb_strtolower($key);
					$value = trim(trim($value), '"');

					if ($key === $tag) {
						$key = 'default';
					}

					if (isset($source['mapAttributes'][$key])) {
						$finalKey = $source['mapAttributes'][$key];

						// Allow for aliasing
						if (isset($source['attributes'][$finalKey])) {
							$key = $finalKey;
						}
					} else {
						$finalKey = $key;
					}

					if (isset($source['attributes'][$key])) {
						$pattern = $source['attributes'][$key];

						if ($pattern === true) {
							$attributes[$finalKey] = $value;

						} else if (is_array($pattern)) {
							if (preg_match($pattern[0], $value)) {
								$attributes[$finalKey] = str_replace('{' . $key . '}', $value, $pattern[1]);
							}

						} else {
							if (preg_match($pattern, $value)) {
								$attributes[$finalKey] = $value;
							}
						}
					}
				}
			}
		}

		if (
			$disabled ||
			($this->_whitelist && !in_array($tag, $this->_whitelist)) ||
			($this->_blacklist && in_array($tag, $this->_blacklist))
		) {
			$type = self::TAG_NONE;
			$string = '';
		}

		return array(
			'tag' => $tag,
			'type' => $type,
			'text' => $string,
			'attributes' => $attributes
		);
	}

	/**
	 * Clean the chunk list by verifying that open and closing tags are nested correctly.
	 *
	 * @param array $chunks
	 * @param array $wrapper
	 * @return string
	 */
	protected function _cleanChunks(array $chunks, array $wrapper = array()) {
		$clean = array();
		$openTags = array();
		$prevChunk = array();
		$disallowed = array();
		$parents = array();
		$depths = array();
		$count = count($chunks);
		$i = 0;

		if ($wrapper) {
			$parent = $this->getFilterByTag($wrapper['tag'])->getTag($wrapper['tag']);
			$root = false;
		} else {
			$parent = $this->getFilter('Empty')->getTag('root');
			$root = true;
		}

		while ($i < $count) {
			$chunk = $chunks[$i];
			$tag = isset($chunk['tag']) ? $chunk['tag'] : '';

			switch ($chunk['type']) {
				case self::TAG_NONE:
					// Disregard deeply nested text nodes if persist is disabled
					if ($disallowed && !$parent['persistContent']) {
						continue;
					}

					if (!$parent['childrenWhitelist'] && !$parent['childrenBlacklist']) {
						if (!empty($prevChunk) && $prevChunk['type'] === self::TAG_NONE) {
							$chunk['text'] = $prevChunk['text'] . $chunk['text'];
							array_pop($clean);
						}

						$clean[] = $chunk;
					}
				break;

				case self::TAG_OPEN:
					if ($parent['maxChildDepth'] >= 0 && !isset($depths[$tag])) {
						$depths[$tag] = 1;
						$parent['currentDepth'] = $depths[$tag];

					} else if (isset($depths[$tag])) {
						$depths[$tag] += 1;
						$parent['currentDepth'] = $depths[$tag];
					}

					if ($this->_isAllowed($parent, $tag)) {
						$prevParent = $parent;
						$parents[] = $parent;
						$parent = $this->getFilterByTag($tag)->getTag($tag);

						// Don't parse Decoda tags if preserve is disabled
						if ($prevParent['preserveTags']) {
							$chunk['type'] = self::TAG_NONE;
							$parent['preserveTags'] = true;
						}

						$clean[] = $chunk;

						if ($root) {
							$openTags[] = array('tag' => $tag, 'index' => $i);
						}
					} else {
						$disallowed[] = array('tag' => $tag, 'index' => $i);
					}
				break;

				case self::TAG_CLOSE:
					// Reduce depth
					if (isset($depths[$tag])) {
						$depths[$tag] -= 1;
					}

					// If something is not allowed, skip the close tag
					if ($disallowed) {
						$last = end($disallowed);

						if ($last['tag'] === $tag) {
							array_pop($disallowed);
							continue;
						}
					}

					// Return to previous parent before allowing
					if ($parents) {
						$parent = array_pop($parents);
					}

					// Now check for open tags if the tag is allowed
					if ($this->_isAllowed($parent, $tag)) {
						if ($parent['preserveTags']) {
							$chunk['type'] = self::TAG_NONE;
						}

						$clean[] = $chunk;

						if ($root && $openTags) {
							$last = end($openTags);

							if ($last['tag'] === $tag) {
								array_pop($openTags);
							} else {
								while ($openTags) {
									$last = array_pop($openTags);

									if ($last['tag'] !== $tag) {
										$this->_errors[] = array(
											'type' => self::ERROR_NESTING,
											'tag' => $last['tag']
										);

										unset($clean[$last['index']]);
									}
								}
							}
						}
					}
				break;

				case self::TAG_SELF_CLOSE:
					$clean[] = $chunk;
				break;
			}

			$i++;
			$prevChunk = $chunk;
		}

		// Remove any unclosed tags
		while ($openTags) {
			$last = array_pop($openTags);

			$this->_errors[] = array(
				'type' => self::ERROR_CLOSING,
				'tag' => $last['tag']
			);

			unset($clean[$last['index']]);
		}

		return array_values($clean);
	}

	/**
	 * Remove any newlines above the max.
	 *
	 * @param string $string
	 * @return string
	 */
	protected function _cleanNewlines($string) {
		$string = trim($string);

		if ($max = $this->getConfig('maxNewlines')) {
			$string = preg_replace('/\n{' . ($max + 1) . ',}/', str_repeat("\n", $max), $string);
		}

		return $string;
	}

	/**
	 * Normalize line feeds and escape HTML characters.
	 *
	 * @param string $string
	 * @return string
	 */
	protected function _escape($string) {
		$string = str_replace("\r\n", "\n", $string);
		$string = str_replace("\r", "\n", $string);

		if ($this->getConfig('escapeHtml')) {
			$string = str_replace(array('<', '>'), array('&lt;', '&gt;'), $string);
		}

		return $string;
	}

	/**
	 * Scan the string stack and extract any tags and chunks of text that were detected.
	 *
	 * @param string $string
	 * @return array
	 */
	protected function _extractChunks($string) {
		$strPos = 0;
		$strLength = mb_strlen($string);
		$openBracket = $this->getConfig('open');
		$closeBracket = $this->getConfig('close');

		while ($strPos < $strLength) {
			$tag = array();
			$openPos = mb_strpos($string, $openBracket, $strPos);

			if ($openPos === false) {
				$openPos = $strLength;
			}

			if ($openPos + 1 > $strLength) {
				$nextOpenPos = $strLength;
			} else {
				$nextOpenPos = mb_strpos($string, $openBracket, $openPos + 1);

				if ($nextOpenPos === false) {
					$nextOpenPos = $strLength;
				}
			}

			$closePos = mb_strpos($string, $closeBracket, $strPos);

			if ($closePos === false) {
				$closePos = $strLength + 1;
			}

			// Possible tag found, lets look
			if ($openPos === $strPos) {

				// Child open tag before closing tag
				if ($nextOpenPos < $closePos) {
					$newPos = $nextOpenPos;
					$tag['text'] = mb_substr($string, $strPos, ($nextOpenPos - $strPos));
					$tag['type'] = self::TAG_NONE;

				// Tag?
				} else {
					$newPos = $closePos + 1;
					$newTag = $this->_buildTag(mb_substr($string, $strPos, (($closePos - $strPos) + 1)));

					// Valid tag
					if ($newTag) {
						$tag = $newTag;

					// Not a valid tag
					} else {
						$tag['text'] = mb_substr($string, $strPos, $closePos - $strPos + 1);
						$tag['type'] = self::TAG_NONE;
					}
				}

			// No tag, just text
			} else {
				$newPos = $openPos;

				$tag['text'] = mb_substr($string, $strPos, ($openPos - $strPos));
				$tag['type'] = self::TAG_NONE;
			}

			// Join consecutive text elements
			if ($tag['type'] === self::TAG_NONE && isset($prev) && $prev['type'] === self::TAG_NONE) {
				$tag['text'] = $prev['text'] . $tag['text'];
				array_pop($this->_chunks);
			}

			$this->_chunks[] = $tag;
			$prev = $tag;
			$strPos = $newPos;
		}

		$this->_nodes = $this->_extractNodes($this->_chunks);

		return $this->_nodes;
	}

	/**
	 * Convert the chunks into a child parent hierarchy of nodes.
	 *
	 * @param array $chunks
	 * @param array $wrapper
	 * @return array
	 */
	protected function _extractNodes(array $chunks, array $wrapper = array()) {
		$chunks = $this->_cleanChunks($chunks, $wrapper);
		$nodes = array();
		$tag = array();
		$openIndex = -1;
		$openCount = -1;
		$closeCount = -1;
		$count = count($chunks);
		$i = 0;

		while ($i < $count) {
			$chunk = $chunks[$i];

			if ($chunk['type'] === self::TAG_NONE && empty($tag)) {
				$nodes[] = $chunk['text'];

			} else if ($chunk['type'] === self::TAG_SELF_CLOSE) {
				$chunk['children'] = array();
				$nodes[] = $chunk;

			} else if ($chunk['type'] === self::TAG_OPEN) {
				$openCount++;

				if (empty($tag)) {
					$openIndex = $i;
					$tag = $chunk;
				}

			} else if ($chunk['type'] === self::TAG_CLOSE) {
				$closeCount++;

				if ($openCount === $closeCount && $chunk['tag'] === $tag['tag']) {
					$index = $i - $openIndex;
					$tag = array();

					// Only reduce if not last index
					if ($index !== $count) {
						$index = $index - 1;
					}

					// Slice a section of the array if the correct closing tag is found
					$node = $chunks[$openIndex];
					$node['children'] = $this->_extractNodes(array_slice($chunks, ($openIndex + 1), $index), $chunks[$openIndex]);
					$nodes[] = $node;

				// There is no opening or a broken opening tag, which means
				// $closeCount should not have been incremented before >> revert
				} else if (empty($tag)) {
					$closeCount--;
				}
			}

			$i++;
		}

		return $nodes;
	}

	/**
	 * Validate that the following child can be nested within the parent.
	 *
	 * @param array $parent
	 * @param string $tag
	 * @return boolean
	 */
	protected function _isAllowed($parent, $tag) {
		$filter = $this->getFilterByTag($tag);

		if (!$filter) {
			return false;
		}

		$child = $filter->getTag($tag);

		// Remove children after a certain nested depth
		if (isset($parent['currentDepth']) && $parent['maxChildDepth'] >= 0 && $parent['currentDepth'] > $parent['maxChildDepth']) {
			return false;

		// Children that can only be within a certain parent
		} else if ($child['parent'] && !in_array($parent['tag'], $child['parent'])) {
			return false;

		// Parents that can not have specific direct descendant children
		} else if ($parent['childrenBlacklist'] && in_array($child['tag'], $parent['childrenBlacklist'])) {
			return false;

		// Parents that can only have direct descendant children
		} else if ($parent['childrenWhitelist'] && !in_array($child['tag'], $parent['childrenWhitelist'])) {
			return false;
		}

		// Validate the type nesting
		switch ($parent['allowedTypes']) {
			case self::TYPE_INLINE:
				// Inline type only allowed
				if ($child['displayType'] === self::TYPE_INLINE) {
					return true;
				}
			break;
			case self::TYPE_BLOCK:
				// Block types only allowed if the parent is also a block
				if ($parent['displayType'] === self::TYPE_BLOCK && $child['displayType'] === self::TYPE_BLOCK) {
					return true;
				}
			break;
			case self::TYPE_BOTH:
				if ($parent['displayType'] === self::TYPE_INLINE) {
					// Only allow inline if parent is inline
					if ($child['displayType'] === self::TYPE_INLINE) {
						return true;
					}
				} else {
					return true;
				}
			break;
		}

		// Log the error
		$this->_errors[] = array(
			'type' => self::ERROR_SCOPE,
			'parent' => $parent['tag'],
			'parentType' => $parent['displayType'],
			'parentAllowed' => $parent['allowedTypes'],
			'child' => $child['tag'],
			'childType' => $child['displayType']
		);

		return false;
	}

	/**
	 * Return true if the string is parseable.
	 *
	 * @param string $string
	 * @return boolean
	 */
	protected function _isParseable($string) {
		return (
			mb_strpos($string, $this->getConfig('open')) !== false &&
			mb_strpos($string, $this->getConfig('close')) !== false &&
			!$this->getConfig('disabled')
		);
	}

	/**
	 * Load in all message strings from the config paths.
	 */
	protected function _loadMessages() {
		foreach ($this->getPaths() as $path) {
			foreach (glob($path . 'messages.*') as $file) {
				$this->addMessages(new \Decoda\Loader\FileLoader($file));
			}
		}
	}

	/**
	 * Cycle through the nodes and parse the string with the appropriate filter.
	 *
	 * @param array $nodes
	 * @param array $wrapper
	 * @return string
	 */
	protected function _parse(array $nodes, array $wrapper = array()) {
		$parsed = '';
		$xhtml = $this->getConfig('xhtmlOutput');

		if (!$nodes) {
			return $parsed;
		}

		foreach ($nodes as $node) {
			if (is_string($node)) {
				if (!$wrapper) {
					$parsed .= nl2br($node, $xhtml);
				} else {
					$parsed .= $node;
				}
			} else {
				$parsed .= $this->getFilterByTag($node['tag'])->parse($node, $this->_parse($node['children'], $node));
			}
		}

		return $parsed;
	}

	/**
	 * Cycle through the nodes and strip out tags and content.
	 *
	 * @param array $nodes
	 * @param array $wrapper
	 * @return string
	 */
	protected function _strip(array $nodes, array $wrapper = array()) {
		$parsed = '';
		$xhtml = $this->getConfig('xhtmlOutput');

		if (!$nodes) {
			return $parsed;
		}

		foreach ($nodes as $node) {
			if (is_string($node)) {
				if (!$wrapper) {
					$parsed .= nl2br($node, $xhtml);
				} else {
					$parsed .= $node;
				}
			} else {
				$parsed .= $this->getFilterByTag($node['tag'])->strip($node, $this->_strip($node['children'], $node));
			}
		}

		return $parsed;
	}

	/**
	 * Trigger all hooks at an event specified by the method name.
	 *
	 * @param string $method
	 * @param string $content
	 * @return string
	 */
	protected function _triggerHook($method, $content = null) {
		if ($this->_hooks) {
			foreach ($this->_hooks as $hook) {
				if (method_exists($hook, $method)) {
					if ($content !== null) {
						$content = $hook->{$method}($content);
					} else {
						$hook->{$method}();
					}
				}
			}
		}

		return $content;
	}

}
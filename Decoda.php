<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda;

use mjohnson\decoda\engines\Engine;
use mjohnson\decoda\filters\Filter;
use mjohnson\decoda\hooks\Hook;
use \Exception;

// Set constant and include path
if (!defined('DECODA')) {
	define('DECODA', __DIR__ . '/');

	set_include_path(get_include_path() . PATH_SEPARATOR . DECODA);
}

/**
 * A lightweight lexical string parser for simple markup syntax.
 * Provides a very powerful filter and hook system to extend the parsing cycle.
 *
 * @package	mjohnson.decoda
 * @version	4.0.0
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
	 * 	NL_PRESERVE	- Will be preserved as \n and \r
	 * 	NL_CONVERT	- Will be converted to <br> tags
	 */
	const NL_REMOVE = 0;
	const NL_PRESERVE = 1;
	const NL_CONVERT = 2;

	/**
	 * Blacklist of tags not to parse.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_blacklist = array();

	/**
	 * Extracted chunks of text and tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_chunks = array();

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'open' => '[',
		'close' => ']',
		'disabled' => false,
		'shorthand' => false,
		'xhtml' => false,
		'escape' => true,
		'strict' => true,
		'locale' => 'en-us'
	);

	/**
	 * Logged errors for incorrectly nested nodes and types.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_errors = array();

	/**
	 * List of all instantiated filter objects.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_filters = array();

	/**
	 * Mapping of tags to its filter object.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_filterMap = array();

	/**
	 * List of all instantiated hook objects.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_hooks = array();

	/**
	 * Message strings for localization purposes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_messages = array();

	/**
	 * Children nodes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_nodes = array();

	/**
	 * The parsed string.
	 *
	 * @access protected
	 * @var string
	 */
	protected $_parsed = '';

	/**
	 * Configuration folder paths.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_paths = array();

	/**
	 * The raw string before parsing.
	 *
	 * @access protected
	 * @var string
	 */
	protected $_string = '';

	/**
	 * List of tags from filters.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array();

	/**
	 * Template engine used for parsing.
	 *
	 * @access protected
	 * @var \mjohnson\decoda\engines\Engine
	 */
	protected $_engine = null;

	/**
	 * Whitelist of tags to parse.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_whitelist = array();

	/**
	 * Store the text and single instance configuration.
	 *
	 * @access public
	 * @param string $string
	 */
	public function __construct($string = '') {
		spl_autoload_register(array($this, 'loadFile'));

		$this->reset($string, true);
		$this->addPath(DECODA . 'config/');
	}

	/**
	 * Add additional filters.
	 *
	 * @access public
	 * @param \mjohnson\decoda\filters\Filter $filter
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function addFilter(Filter $filter) {
		$filter->setParser($this);

		$class = str_replace('Filter', '', basename(get_class($filter)));
		$tags = $filter->tags();

		$this->_filters[$class] = $filter;
		$this->_tags = $tags + $this->_tags;

		foreach ($tags as $tag => $options) {
			$this->_filterMap[$tag] = $class;
		}

		$filter->setupHooks($this);

		return $this;
	}

	/**
	 * Add hooks that are triggered at specific events.
	 *
	 * @access public
	 * @param \mjohnson\decoda\hooks\Hook $hook
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function addHook(Hook $hook) {
		$hook->setParser($this);

		$class = str_replace('Hook', '', basename(get_class($hook)));

		$this->_hooks[$class] = $hook;

		$hook->setupFilters($this);

		return $this;
	}

	/**
	 * Add a configuration lookup path.
	 *
	 * @access public
	 * @param string $path
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function addPath($path) {
		$this->_paths[] = $path;

		return $this;
	}

	/**
	 * Add tags to the blacklist.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function blacklist() {
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$args = $args[0];
		}

		$this->_blacklist +=  $args;
		$this->_blacklist = array_filter($this->_blacklist);

		return $this;
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
	 * Apply default filters and hooks if none are set.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function defaults() {
		$this->addFilter(new \mjohnson\decoda\filters\DefaultFilter());
		$this->addFilter(new \mjohnson\decoda\filters\EmailFilter());
		$this->addFilter(new \mjohnson\decoda\filters\ImageFilter());
		$this->addFilter(new \mjohnson\decoda\filters\UrlFilter());
		$this->addFilter(new \mjohnson\decoda\filters\TextFilter());
		$this->addFilter(new \mjohnson\decoda\filters\BlockFilter());
		$this->addFilter(new \mjohnson\decoda\filters\VideoFilter());
		$this->addFilter(new \mjohnson\decoda\filters\CodeFilter());
		$this->addFilter(new \mjohnson\decoda\filters\QuoteFilter());
		$this->addFilter(new \mjohnson\decoda\filters\ListFilter());

		$this->addHook(new \mjohnson\decoda\hooks\CensorHook());
		$this->addHook(new \mjohnson\decoda\hooks\ClickableHook());

		return $this;
	}

	/**
	 * Toggle parsing.
	 *
	 * @access public
	 * @param boolean $status
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function disable($status = true) {
		$this->_config['disabled'] = (bool) $status;

		return $this;
	}

	/**
	 * Return the parsing errors.
	 *
	 * @access public
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
	 * @access public
	 * @param string $filter
	 * @return \mjohnson\decoda\filters\Filter
	 * @throws \Exception
	 */
	public function getFilter($filter) {
		if (isset($this->_filters[$filter])) {
			return $this->_filters[$filter];
		}

		throw new Exception(sprintf('Filter %s does not exist.', $filter));
	}

	/**
	 * Return a filter based on its supported tag.
	 *
	 * @access public
	 * @param string $tag
	 * @return \mjohnson\decoda\filters\Filter
	 * @throws \Exception
	 */
	public function getFilterByTag($tag) {
		if (isset($this->_filterMap[$tag])){
			return $this->getFilter($this->_filterMap[$tag]);
		}

		throw new Exception(sprintf('No filter could be located for tag %s.', $tag));
	}

	/**
	 * Return all filters.
	 *
	 * @access public
	 * @return array
	 */
	public function getFilters() {
		return $this->_filters;
	}

	/**
	 * Return a specific hook based on class name.
	 *
	 * @access public
	 * @param string $hook
	 * @return \mjohnson\decoda\hooks\Hook
	 * @throws \Exception
	 */
	public function getHook($hook) {
		if (isset($this->_hooks[$hook])) {
			return $this->_hooks[$hook];
		}

		throw new Exception(sprintf('Hook %s does not exist.', $hook));
	}

	/**
	 * Return all hooks.
	 *
	 * @access public
	 * @return array
	 */
	public function getHooks() {
		return $this->_hooks;
	}

	/**
	 * Returns the current used template engine.
	 * In case no engine is set the default php engine gonna be used.
	 *
	 * @access public
	 * @return \mjohnson\decoda\engines\Engine
	 */
	public function getEngine() {
		if (!$this->_engine) {
			$this->_engine = new \mjohnson\decoda\engines\PhpEngine();
		}

		return $this->_engine;
	}

	/**
	 * Return the configuration folder paths.
	 *
	 * @access public
	 * @return array
	 */
	public function getPaths() {
		return $this->_paths;
	}

	/**
	 * Autoload filters and hooks.
	 *
	 * @access public
	 * @param string $class
	 * @return void
	 */
	public function loadFile($class) {
		if (class_exists($class) || interface_exists($class)) {
			return;
		}

		$paths = array(str_replace('\\', '/', $class) . '.php');
		$paths[] = DECODA . str_replace('mjohnson/decoda/', '', $paths[0]);

		foreach ($paths as $path) {
			if (file_exists($path)) {
				include_once $path;
			}
		}
	}

	/**
	 * Return a message string if it exists.
	 *
	 * @access public
	 * @param string $key
	 * @param array $vars
	 * @return string
	 */
	public function message($key, array $vars = array()) {
		if (!$this->_messages) {
			$messages = array();

			if ($paths = $this->getPaths()) {
				foreach ($paths as $path) {
					if (file_exists($path . 'messages.json')) {
						$messages = array_merge($messages, json_decode(file_get_contents($path . 'messages.json'), true));
					}
				}
			}

			$this->_messages = $messages;
		}

		$locale = $this->config('locale');
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
	 * @access public
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

		if (!$this->_filters && !$this->_hooks) {
			return $this->_string;
		}

		ksort($this->_hooks);

		if ($this->config('escape')) {
			$this->_string = str_replace(array('<', '>'), array('&lt;', '&gt;'), $this->_string);
		}

		$this->_string = $this->_trigger('beforeParse', $this->_string);

		if (strpos($this->_string, $this->config('open')) !== false && strpos($this->_string, $this->config('close')) !== false) {
			$this->_extractChunks();
			$this->_parsed = $this->_parse($this->_nodes);
		} else {
			$this->_parsed = nl2br($this->_string, $this->config('xhtml'));
		}

		$this->_parsed = $this->_trigger('afterParse', $this->_parsed);

		if ($echo) {
			echo $this->_parsed;
		}

		return $this->_parsed;
	}

	/**
	 * Remove filter(s).
	 *
	 * @access public
	 * @param string|array $filters
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function removeFilter($filters) {
		foreach ((array) $filters as $filter) {
			unset($this->_filters[$filter]);

			foreach ($this->_filterMap as $tag => $fil) {
				if ($fil === $filter) {
					unset($this->_filterMap[$tag]);
				}
			}
		}

		return $this;
	}

	/**
	 * Remove hook(s).
	 *
	 * @access public
	 * @param string|array $hooks
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
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
	 * @access public
	 * @param string $string
	 * @param boolean $flush
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function reset($string, $flush = false) {
		$this->_chunks = array();
		$this->_nodes = array();
		$this->_blacklist = array();
		$this->_whitelist = array();
		$this->_string = (string) $string;
		$this->_parsed = '';

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
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function resetFilters() {
		$this->_filters = array();
		$this->_filterMap = array();
		$this->_tags = array();

		$this->addFilter(new \mjohnson\decoda\filters\EmptyFilter());

		return $this;
	}

	/**
	 * Reset all hooks.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function resetHooks() {
		$this->_hooks = array();

		$this->addHook(new \mjohnson\decoda\hooks\EmptyHook());

		return $this;
	}

	/**
	 * Change the open/close markup brackets.
	 *
	 * @access public
	 * @param string $open
	 * @param string $close
	 * @return \mjohnson\decoda\Decoda
	 * @throws \Exception
	 * @chainable
	 */
	public function setBrackets($open, $close) {
		if (empty($open) || empty($close)) {
			throw new Exception('Both the open and close brackets are required.');
		}

		$this->_config['open'] = (string) $open;
		$this->_config['close'] = (string) $close;

		return $this;
	}

	/**
	 * Toggle XSS escaping.
	 *
	 * @access public
	 * @param boolean $status
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function setEscaping($status = true) {
		$this->_config['escape'] = (bool) $status;

		return $this;
	}

	/**
	 * Set the locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return \mjohnson\decoda\Decoda
	 * @throws \Exception
	 * @chainable
	 */
	public function setLocale($locale) {
		$this->message(null);

		if (empty($this->_messages[$locale])) {
			throw new Exception(sprintf('Localized strings for %s do not exist.', $locale));
		}

		$this->_config['locale'] = $locale;

		return $this;
	}

	/**
	 * Toggle shorthand syntax.
	 *
	 * @access public
	 * @param boolean $status
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function setShorthand($status = true) {
		$this->_config['shorthand'] = (bool) $status;

		return $this;
	}

	/**
	 * Toggle strict parsing.
	 *
	 * @access public
	 * @param boolean $strict
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function setStrict($strict = true) {
		$this->_config['strict'] = (bool) $strict;

		return $this;
	}

	/**
	 * Sets the template engine which gonna be used for all tags with templates.
	 *
	 * @access public
	 * @param \mjohnson\decoda\engines\Engine $engine
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function setEngine(Engine $engine) {
		$this->_engine = $engine;

		return $this;
	}

	/**
	 * Toggle XHTML.
	 *
	 * @access public
	 * @param boolean $status
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function setXhtml($status = true) {
		$this->_config['xhtml'] = (bool) $status;

		return $this;
	}

	/**
	 * Add tags to the whitelist.
	 *
	 * @access public
	 * @return \mjohnson\decoda\Decoda
	 * @chainable
	 */
	public function whitelist() {
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$args = $args[0];
		}

		$this->_whitelist +=  $args;
		$this->_whitelist = array_filter($this->_whitelist);

		return $this;
	}

	/**
	 * Determine if the string is an open or closing tag. If so, parse out the attributes.
	 *
	 * @access protected
	 * @param string $string
	 * @return array
	 */
	protected function _buildTag($string) {
		$disabled = $this->config('disabled');
		$tag = array(
			'tag' => '',
			'text' => $string,
			'attributes' => array()
		);

		// Closing tag
		if (substr($string, 1, 1) === '/') {
			$tag['tag'] = trim(substr($string, 2, strlen($string) - 3));
			$tag['type'] = self::TAG_CLOSE;

			if (!isset($this->_tags[$tag['tag']])) {
				return false;
			}

		// Self closing tag
		} else if (substr($string, -2) === '/]') {
			$tag['tag'] = trim(substr($string, 1, strlen($string) - 3));
			$tag['type'] = self::TAG_SELF_CLOSE;

			if (!isset($this->_tags[$tag['tag']])) {
				return false;
			}

		// Opening tag
		} else {
			if (strpos($string, ' ') && (strpos($string, '=') === false)) {
				return false;
			}

			// Find tag
			$oe = preg_quote($this->config('open'));
			$ce = preg_quote($this->config('close'));

			if (preg_match('/' . $oe . '([a-z0-9]+)(.*?)' . $ce . '/i', $string, $matches)) {
				$tag['type'] = self::TAG_OPEN;
				$tag['tag'] = trim($matches[1]);
			}

			if (!isset($this->_tags[$tag['tag']])) {
				return false;
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
				if (!$this->config('strict')) {
					preg_match_all('/([a-z]+)=([^\s\]]+)/i', $string, $matches, PREG_SET_ORDER);

					if ($matches) {
						foreach ($matches as $match) {
							if (!isset($found[$match[1]])) {
								$found[$match[1]] = $match[2];
							}
						}
					}
				}

				if ($found) {
					$source = $this->_tags[$tag['tag']];

					foreach ($found as $key => $value) {
						$key = strtolower($key);
						$value = trim(trim($value), '"');

						if ($key === $tag['tag']) {
							$key = 'default';
						}

						if (isset($source['mapAttributes'][$key])) {
							$finalKey = $source['mapAttributes'][$key];
						} else {
							$finalKey = $key;
						}

						if (isset($source['attributes'][$key])) {
							$pattern = $source['attributes'][$key];

							if ($pattern === true) {
								$tag['attributes'][$finalKey] = $value;

							} else if (is_array($pattern)) {
								if (preg_match($pattern[0], $value)) {
									$tag['attributes'][$finalKey] = str_replace('{' . $key . '}', $value, $pattern[1]);
								}

							} else {
								if (preg_match($pattern, $value)) {
									$tag['attributes'][$finalKey] = $value;
								}
							}
						}
					}
				}
			}
		}

		if (
			$disabled ||
			($this->_whitelist && !in_array($tag['tag'], $this->_whitelist)) ||
			($this->_blacklist && in_array($tag['tag'], $this->_blacklist))
		) {
			$tag['type'] = self::TAG_NONE;
			$tag['text'] = '';
		}

		return $tag;
	}

	/**
	 * Clean the chunk list by verifying that open and closing tags are nested correctly.
	 *
	 * @access protected
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
		$tag = '';
		$i = 0;

		if ($wrapper) {
			$parent = $this->getFilterByTag($wrapper['tag'])->tag($wrapper['tag']);
			$root = false;
		} else {
			$parent = $this->getFilter('Empty')->tag('root');
			$root = true;
		}

		while ($i < $count) {
			$chunk = $chunks[$i];
			$tag = isset($chunk['tag']) ? $chunk['tag'] : '';

			switch ($chunk['type']) {
				case self::TAG_NONE:
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
						$parent = $this->getFilterByTag($tag)->tag($tag);

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
	 * Scan the string stack and extract any tags and chunks of text that were detected.
	 *
	 * @access protected
	 * @return void
	 */
	protected function _extractChunks() {
		$str = $this->_string;
		$strPos = 0;
		$strLength = strlen($str);
		$openBracket = $this->config('open');
		$closeBracket = $this->config('close');

		while ($strPos < $strLength) {
			$tag = array();
			$openPos = strpos($str, $openBracket, $strPos);

			if ($openPos === false) {
				$openPos = $strLength;
				$nextOpenPos = $strLength;
			}

			if ($openPos + 1 > $strLength) {
				$nextOpenPos = $strLength;
			} else {
				$nextOpenPos = strpos($str, $openBracket, $openPos + 1);

				if ($nextOpenPos === false) {
					$nextOpenPos = $strLength;
				}
			}

			$closePos = strpos($str, $closeBracket, $strPos);

			if ($closePos === false) {
				$closePos = $strLength + 1;
			}

			// Possible tag found, lets look
			if ($openPos === $strPos) {

				// Child open tag before closing tag
				if ($nextOpenPos < $closePos) {
					$newPos = $nextOpenPos;
					$tag['text'] = substr($str, $strPos, ($nextOpenPos - $strPos));
					$tag['type'] = self::TAG_NONE;

				// Tag?
				} else {
					$newPos = $closePos + 1;
					$newTag = $this->_buildTag(substr($str, $strPos, (($closePos - $strPos) + 1)));

					// Valid tag
					if ($newTag) {
						$tag = $newTag;

					// Not a valid tag
					} else {
						$tag['text'] = substr($str, $strPos, $closePos - $strPos + 1);
						$tag['type'] = self::TAG_NONE;
					}
				}

			// No tag, just text
			} else {
				$newPos = $openPos;

				$tag['text'] = substr($str, $strPos, ($openPos - $strPos));
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
	}

	/**
	 * Convert the chunks into a child parent hierarchy of nodes.
	 *
	 * @access protected
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
		$closeIndex = -1;
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
					$closeIndex = $i;
					$index = ($closeIndex - $openIndex);
					$tag = array();

					// Only reduce if not last index
					if ($index !== $count) {
						$index = $index - 1;
					}

					// Slice a section of the array if the correct closing tag is found
					$node = $chunks[$openIndex];
					$node['children'] = $this->_extractNodes(array_slice($chunks, ($openIndex + 1), $index), $chunks[$openIndex]);
					$nodes[] = $node;
				}
			}

			$i++;
		}

		return $nodes;
	}

	/**
	 * Validate that the following child can be nested within the parent.
	 *
	 * @access protected
	 * @param array $parent
	 * @param string $tag
	 * @return boolean
	 */
	protected function _isAllowed($parent, $tag) {
		$filter = $this->getFilterByTag($tag);

		if (!$filter) {
			return false;
		}

		$child = $filter->tag($tag);

		// Remove children after a certain nested depth
		if (isset($parent['currentDepth']) && $parent['currentDepth'] > $parent['maxChildDepth']) {
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
	 * Cycle through the nodes and parse the string with the appropriate filter.
	 *
	 * @access protected
	 * @param array $nodes
	 * @param array $wrapper
	 * @return string
	 */
	protected function _parse(array $nodes, array $wrapper = array()) {
		$parsed = '';
		$xhtml = $this->config('xhtml');

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
	 * Trigger all hooks at an event specified by the method name.
	 *
	 * @access protected
	 * @param string $method
	 * @param string $content
	 * @return string
	 */
	protected function _trigger($method, $content) {
		if ($this->_hooks) {
			foreach ($this->_hooks as $hook) {
				if (method_exists($hook, $method)) {
					$content = $hook->{$method}($content);
				}
			}
		}

		return $content;
	}

}
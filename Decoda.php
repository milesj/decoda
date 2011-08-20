<?php
/**
 * Decoda - Lightweight Markup Language
 *
 * Processes and translates custom markup (BB code style), functionality for word censoring, emoticons and GeSHi code highlighting.
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2006-2011, Miles Johnson, Inc.
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		http://milesj.me/code/php/decoda
 */

// Constants
define('DECODA', dirname(__FILE__) .'/');
define('DECODA_HOOKS', DECODA .'hooks/');
define('DECODA_CONFIG', DECODA .'config/');
define('DECODA_FILTERS', DECODA .'filters/');
define('DECODA_TEMPLATES', DECODA .'templates/');
define('DECODA_EMOTICONS', DECODA .'emoticons/');

// Includes
spl_autoload_register();
set_include_path(implode(PATH_SEPARATOR, array(
	get_include_path(),
	DECODA, DECODA_HOOKS, 
	DECODA_CONFIG, DECODA_FILTERS,
	DECODA_TEMPLATES, DECODA_EMOTICONS
)));

class Decoda {

	/**
	 * Tag type constants.
	 */
	const TAG_OPEN = 1;
	const TAG_CLOSE = 2;
	const TAG_NONE = 0;

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
		'parse' => true,
		'shorthand' => false,
		'xhtml' => false,
		'locale' => 'en-us'
	);

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
	 * @param array $config 
	 * @return void
	 */
	public function __construct($string, array $config = array()) {
		$this->configure($config);
		$this->reset($string);
	}

	/**
	 * Add additional filters.
	 * 
	 * @access public
	 * @param DecodaFilter $filter 
	 * @return Decoda
	 * @chainable
	 */
	public function addFilter(DecodaFilter $filter) {
		$filter->setParser($this);

		$class = str_replace('Filter', '', get_class($filter));
		$tags = $filter->tags();

		$this->_filters[$class] = $filter;
		$this->_tags = $tags + $this->_tags;

		foreach ($tags as $tag => $options) {
			$this->_filterMap[$tag] = $class;
		}

		return $this;
	}

	/**
	 * Add hooks that are triggered at specific events.
	 * 
	 * @access public
	 * @param DecodaHook $hook
	 * @return Decoda 
	 * @chainable
	 */
	public function addHook(DecodaHook $hook) {
		$hook->setParser($this);

		$this->_hooks[] = $hook;

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
	 * Apply configuration.
	 *
	 * @access public
	 * @param string $options
	 * @param mixed $value
	 * @return Decoda
	 * @chainable
	 */
	public function configure($options, $value = true) {
		if (is_array($options)) {
			foreach ($options as $option => $value) {
				$this->configure($option, $value);
			}
		} else {
			if (isset($this->_config[$options])) {
				$this->_config[$options] = $value;
			} else {
				throw new Exception(sprintf('Configuration %s does not exist.', $options));
			}
		}

		return $this;
	}
	
	/**
	 * Disable all filters.
	 *
	 * @access public
	 * @return Decoda
	 * @chainable
	 */
	public function disableFilters() {
		$this->_filters = array();
		$this->_filterMap = array();
		
		$this->addFilter(new EmptyFilter());
		
		return $this;
	}
	
	/**
	 * Disable all hooks.
	 *
	 * @access public
	 * @return Decoda
	 * @chainable
	 */
	public function disableHooks() {
		$this->_hooks = array();
		
		$this->addHook(new EmptyHook());
		
		return $this;
	}

	/**
	 * Return a specific filter based on class name or tag.
	 * 
	 * @access public
	 * @param string $filter
	 * @return DecodaFilter
	 */
	public function getFilter($filter) {
		return isset($this->_filters[$filter]) ? $this->_filters[$filter] : null;
	}

	/**
	 * Return a filter based on its supported tag.
	 * 
	 * @access public
	 * @param string $tag
	 * @return DecodaFilter
	 */
	public function getFilterByTag($tag) {
		return isset($this->_filterMap[$tag]) ? $this->_filters[$this->_filterMap[$tag]] : null;
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
	 * Validate that the following child can be nested within the parent.
	 * 
	 * @access public
	 * @param array $parent
	 * @param string $tag
	 * @return boolean 
	 */
	public function isAllowed($parent, $tag) {
		$filter = $this->getFilterByTag($tag);

		if (!$filter) {
			return false;
		}

		$child = $filter->tag($tag);

		// Remove children after a certain nested depth
		if (isset($parent['currentDepth']) && $parent['currentDepth'] > $parent['maxChildDepth']) {
			return false;

		// Children that can only be within a certain parent
		} else if (!empty($child['parent']) && !in_array($parent['key'], $child['parent'])) {
			return false;

		// Parents that can only have direct descendant children
		} else if (!empty($parent['children']) && !in_array($child['key'], $parent['children'])) {
			return false;

		// Block element that accepts both types
		} else if ($parent['allowed'] == DecodaFilter::TYPE_BOTH) {
			return true;

		// Inline elements can go within everything
		} else if (($parent['allowed'] == DecodaFilter::TYPE_INLINE || $parent['allowed'] == DecodaFilter::TYPE_BLOCK) && $child['type'] == DecodaFilter::TYPE_INLINE) {
			return true;
		}

		return false;
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
		$locale = $this->config('locale');

		if (empty($this->_messages[$locale])) {
			throw new Exception(sprintf('Localized strings for %s do not exist.', $locale));
		}

		$string = isset($this->_messages[$locale][$key]) ? $this->_messages[$locale][$key] : '';

		if (!empty($vars)) {
			foreach ($vars as $key => $value) {
				$string = str_replace('{'. $key .'}', $value, $string);
			}
		}

		return $string;
	}

	/**
	 * Parse the node list by looping through each one, validating, applying filters, building and finally concatenating the string.
	 * 
	 * @access public
	 * @return string
	 */
	public function parse() {
		if (!empty($this->_parsed)) {
			return $this->_parsed;
		}

		$this->_defaults();
		$this->_string = $this->_trigger('beforeParse', $this->_string);

		if ($this->config('parse')) {
			$this->_extractChunks();
			$this->_parsed = $this->_parse($this->_nodes);
		} else {
			$this->_parsed = nl2br($this->_string);
		}

		$this->_parsed = $this->_trigger('afterParse', $this->_parsed);

		return $this->_parsed;
	}

	/**
	 * Reset the parser to a new string.
	 *
	 * @access public
	 * @param string $string
	 * @return Decoda
	 * @chainable
	 */
	public function reset($string) {
		if ((strpos($string, $this->config('open')) === false) && (strpos($string, $this->config('close')) === false)) {
			$this->configure('parse', false);
		} else {
			$this->_messages = json_decode(file_get_contents(DECODA_CONFIG .'messages.json'), true);
		}

		$this->_string = $string;

		return $this;
	}
	
	/**
	 * Add tags to the whitelist.
	 * 
	 * @access public
	 * @return Decoda
	 * @chainable 
	 */
	public function whitelist() {
		$this->_whitelist += array_map('strtolower', func_get_args());
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
		$tag = array(
			'tag' => '',
			'text' => $string, 
			'attributes' => array()
		);

		// Closing tag
		if (substr($string, 1, 1) == '/') {
			$tag['tag'] = strtolower(substr($string, 2, strlen($string) - 3));
			$tag['type'] = self::TAG_CLOSE;

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

			if (preg_match('/'. $oe .'([a-z0-9]+)(.*?)'. $ce .'/i', $string, $matches)) {
				$tag['type'] = self::TAG_OPEN;
				$tag['tag'] = strtolower($matches[1]);
			}

			if (!isset($this->_tags[$tag['tag']])) {
				return false;
			}

			// Find attributes
			preg_match_all('/([a-z]+)=\"(.*?)\"/i', $string, $matches, PREG_SET_ORDER);

			if (!empty($matches)) {
				$source = $this->_tags[$tag['tag']];

				foreach ($matches as $match) {
					$key = strtolower($match[1]);
					$value = trim($match[2]);

					if ($key == $tag['tag']) {
						$key = 'default';
					}

					if (isset($source['attributes'][$key])) {
						$pattern = $source['attributes'][$key];
						
						if (is_array($pattern)) {
							if (preg_match($pattern[0], $value)) {
								$tag['attributes'][$key] = str_replace('{'. $key .'}', $value, $pattern[1]);
							}
						} else {
							if (preg_match($pattern, $value)) {
								$tag['attributes'][$key] = $value;
							}
						}
					}
				}
			}
		}
		
		if (!empty($this->_whitelist) && !in_array($tag['tag'], $this->_whitelist)) {
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

		if (!empty($wrapper)) {
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
					if (empty($disallowed)) {
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

					if ($this->isAllowed($parent, $tag)) {
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
					if (!empty($disallowed)) {
						$last = end($disallowed);

						if ($last['tag'] == $tag) {
							array_pop($disallowed);
							continue;
						}
					}

					// Return to previous parent before allowing
					if (!empty($parents)) {
						$parent = array_pop($parents);
					}

					// Now check for open tags if the tag is allowed
					if ($this->isAllowed($parent, $tag)) {
						if ($parent['preserveTags']) {
							$chunk['type'] = self::TAG_NONE;
						}

						$clean[] = $chunk;

						if ($root && !empty($openTags)) {
							$last = end($openTags);

							if ($last['tag'] == $tag) {
								array_pop($openTags);
							} else {
								while (!empty($openTags)) {
									$last = array_pop($openTags);

									if ($last['tag'] != $tag) {
										unset($clean[$last['index']]);
									}
								}
							}
						}
					}
				break;
			}

			$i++;
			$prevChunk = $chunk;
		}

		// Remove any unclosed tags
		while (!empty($openTags)) {
			$last = array_pop($openTags);

			unset($clean[$last['index']]);
		}

		return $clean;
	}

	/**
	 * Apply default filters and hooks if none are set.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _defaults() {			
		if (empty($this->_filters)) {
			$this->addFilter(new DefaultFilter());
			$this->addFilter(new EmailFilter());
			$this->addFilter(new ImageFilter());
			$this->addFilter(new UrlFilter());
			$this->addFilter(new TextFilter());
			$this->addFilter(new BlockFilter());
			$this->addFilter(new VideoFilter());
			$this->addFilter(new CodeFilter());
			$this->addFilter(new QuoteFilter());
			$this->addFilter(new ListFilter());
		}

		if (empty($this->_hooks)) {
			$this->addHook(new CensorHook());
			$this->addHook(new ClickableHook());
			$this->addHook(new EmoticonHook());
		}

		$this->addFilter(new EmptyFilter());
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

		while ($strPos < $strLength) {
			$tag = array();
			$openPos = strpos($str, $this->config('open'), $strPos);

			if ($openPos === false) {
				$openPos = $strLength;
				$nextOpenPos = $strLength;
			}

			if ($openPos + 1 > $strLength) {
				$nextOpenPos = $strLength;
			} else {
				$nextOpenPos = strpos($str, $this->config('open'), $openPos + 1);

				if ($nextOpenPos === false) {
					$nextOpenPos = $strLength;
				}
			}

			$closePos = strpos($str, $this->config('close'), $strPos);

			if ($closePos === false) {
				$closePos = $strLength + 1;
			}

			// Possible tag found, lets look
			if ($openPos == $strPos) {

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
				if ($newTag !== false) {
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

			if ($chunk['type'] == self::TAG_NONE && empty($tag)) {
				$nodes[] = $chunk['text'];

			} else if ($chunk['type'] == self::TAG_OPEN) {
				$openCount++;

				if (empty($tag)) {
					$openIndex = $i;
					$tag = $chunk;
				}

			} else if ($chunk['type'] == self::TAG_CLOSE) {
				$closeCount++;

				if ($openCount == $closeCount && $chunk['tag'] == $tag['tag']) {
					$closeIndex = $i;
					$index = ($closeIndex - $openIndex);
					$tag = array();

					// Only reduce if not last index
					if ($index != $count) {
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
	 * Cycle through the nodes and parse the string with the appropriate filter.
	 * 
	 * @access protected
	 * @param array $nodes
	 * @param array $wrapper
	 * @return string 
	 */
	protected function _parse(array $nodes, array $wrapper = array()) {
		$parsed = '';

		if (empty($nodes)) {
			return $parsed;
		}

		foreach ($nodes as $node) {
			if (is_string($node)) {
				if (empty($wrapper)) {
					$parsed .= nl2br($node);
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
		if (!empty($this->_hooks)) {
			foreach ($this->_hooks as $hook) {
				if (method_exists($hook, $method)) {
					$content = $hook->{$method}($content);
				}
			}
		}

		return $content;
	}

}
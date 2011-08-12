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

class Decoda extends DecodaNode {
	
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
		'quoteDepth' => 2,
		'childQuotes' => false,
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
	 * The root node object.
	 * 
	 * @access protected
	 * @var DecodaNode
	 */
	protected $_node;

	/**
	 * List of tags from filters.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array();
	
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
	 * Return a message string if it exists.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public function message($key) {
		$locale = $this->config('locale');
		
		if (empty($this->_messages[$locale])) {
			throw new Exception(sprintf('Localized strings for %s do not exist.', $locale));
		}
		
		return isset($this->_messages[$locale][$key]) ? $this->_messages[$locale][$key] : '';
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
		
		if ($this->config('parse')) {
			$this->_parseChunks();
			$this->_parsed = $this->_node->parse();
		} else {
			$this->_parsed = $this->_string;
		}
			
		$this->_trigger('parse');
		
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
				$tag['tag'] = $matches[1];
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
					$value = trim($match[2], $this->config('close'));
					
					if ($key == $tag['tag']) {
						$key = 'default';
					}
					
					if (isset($source['attributes'][$key])) {
						if (preg_match($source['attributes'][$key], $value)) {
							$tag['attributes'][$key] = $value;
						}
					}
				}
			}
        }
		
		return $tag;
    }
	
	/**
	 * Apply default filters if none are set.
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
		}
	}
	
	/**
	 * Scan the string stack and extract any tags and chunks of text that were detected.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function _parseChunks() {
        $this->_chunks = array();
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
		
		// Convert the discovered tags into nodes
		$this->_node = new DecodaNode($this->_chunks, $this);
    }
	
	/**
	 * Trigger all hooks at an event specified by the method name.
	 * 
	 * @access protected
	 * @param string $method 
	 * @return void
	 */
	protected function _trigger($method) {
		if (!empty($this->_hooks)) {
			foreach ($this->_hooks as $hook) {
				if (method_exists($hook, $method)) {
					$this->_parsed = $hook->{$method}($this->_parsed);
				}
			}
		}
	}
	
}
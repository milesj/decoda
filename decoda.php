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
define('DECODA_GESHI', DECODA .'geshi/');
define('DECODA_CONFIG', DECODA .'config/');
define('DECODA_FILTERS', DECODA .'filters/');
define('DECODA_EMOTICONS', DECODA .'emoticons/');

// Includes
spl_autoload_register();
set_include_path(implode(PATH_SEPARATOR, array(
	get_include_path(),
	DECODA, DECODA_GESHI, DECODA_CONFIG, DECODA_FILTERS
)));

class Decoda {
	
	/**
	 * Tag type constants.
	 */
	const TAG_OPEN = 1;
	const TAG_CLOSE = 2;
	const TAG_NONE = 0;
	
	/**
	 * Configuration.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'open' => '[',
		'close' => ']',
		'geshi' => true,
		'parse' => true,
		'censor' => true,
		'emoticons' => true,
		'clickable' => true,
		'shorthand' => false,
		'xhtml' => false,
		'quoteDepth' => 2,
		'childQuotes' => false,
		'jquery' => false
	);
	
	/**
	 * Extracted chunks of text and tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_chunks = array();
	
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
	 * The root node object.
	 * 
	 * @access protected
	 * @var DecodaNode
	 */
	protected $_node;

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
	 * List of all tags from all filters.
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
	 * @return this
	 * @chainable
	 */
	public function add(DecodaFilter $filter) {
		$class = get_class($filter);
		$tags = $filter->tags();
		
		$this->_filters[$class] = $filter;
		$this->_tags = $tags + $this->_tags;
		
		foreach ($tags as $tag => $options) {
			$this->_filterMap[$tag] = $class;
		}
		
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
	 * @return this
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
			}
		}

		return $this;
	}
	
	/**
	 * Return a specific filter based on class name or tag.
	 * 
	 * @access public
	 * @param string $filter
	 * @return array|null
	 */
	public function filter($filter) {
		if (isset($this->_filters[$filter])) {
			return $this->_filters[$filter];

		} else if (isset($this->_filterMap[$filter])) {
			return $this->_filters[$this->_filterMap[$filter]];
		}
		
		return null;
	}
	
	/**
	 * Return all filters.
	 * 
	 * @access public
	 * @return array
	 */
	public function filters() {
		return $this->_filters;
	}
	
	/**
	 * Parse the node list by looping through each one, validating, applying filters, building and finally concatenating the string.
	 * 
	 * @access public
	 * @return string
	 */
	public function parse() {
		if (!$this->config('parse')) {
			$this->_parsed = $this->_string;
		}
		
		if (!empty($this->_parsed)) {
			return $this->_parsed;
		}
		
		// If no filters added, setup defaults
		if (empty($this->_filters)) {
			$this->add(new DefaultFilter());
			$this->add(new EmailFilter());
			$this->add(new ImageFilter());
			$this->add(new UrlFilter());
			$this->add(new TextFilter());
			$this->add(new BlockFilter());
			$this->add(new VideoFilter());
		}
		
        $this->_parseChunks();
		$this->_parsed = $this->_node->parse();
		
		return $this->_parsed;
    }

	/**
	 * Reset the parser to a new string.
	 *
	 * @access public
	 * @param string $string
	 * @return this
	 * @chainable
	 */
	public function reset($string) {
		if ((strpos($string, $this->config('open')) === false) && (strpos($string, $this->config('close')) === false)) {
			$this->configure('parse', false);
        }
		
		$this->_string = $string;
		
		return $this;
	}
	
	/**
	 * Return a single tag.
	 * 
	 * @access public
	 * @param string $tag
	 * @return array|null
	 */
	public function tag($tag) {
		return isset($this->_tags[$tag]) ? $this->_tags[$tag] : null;
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
		
		// Convert the discovered tags into concatenated nodes
		$this->_node = new DecodaNode($this->_chunks, $this);
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
			preg_match_all('/([a-z]+)=(.*)/i', $string, $matches, PREG_SET_ORDER);

			if (!empty($matches)) {
				$source = $this->_tags[$tag['tag']];
				
				foreach ($matches as $match) {
					$key = strtolower($match[1]);
					$value = trim(trim($match[2], $this->config('close')), '"');
					
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
	
}
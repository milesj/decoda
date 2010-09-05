<?php
/**
 * Decoda - Lightweight Markup Language
 *
 * Processes and translates custom markup (BB code style), functionality for word censoring, emoticons and GeSHi code highlighting.
 *
 * @author		Miles Johnson - www.milesj.me
 * @copyright	Copyright 2006-2010, Miles Johnson, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		http://milesj.me/resources/script/decoda
 */

// Constants
define('DECODA', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('DECODA_GESHI', DECODA .'geshi'. DIRECTORY_SEPARATOR);
define('DECODA_EMOTICONS', DECODA .'emoticons'. DIRECTORY_SEPARATOR);
define('DECODA_CONFIG', DECODA .'config'. DIRECTORY_SEPARATOR);

// Includes
include_once DECODA .'config.php';

class Decoda {

    /**
     * Current version: http://milesj.me/resources/logs/decoda
     *
     * @access private
     * @var string
     */
    public $version = '2.9.8';

    /**
     * List of tags allowed to parse.
     *
     * @access private
     * @var array
     */
    private $__allowed = array();

    /**
     * Decoda configuration.
     *
     * @access private
     * @var array
     */
    private $__config = array(
        'geshi'         => true,
        'parse'         => true,
        'censor'        => true,
        'emoticons'     => true,
        'clickable'     => true,
        'shorthand'     => false,
        'xhtml'         => false,
        'childQuotes'   => false,
		'jquery'		=> false
    );

    /**
     * Holds the processed text block.
     *
     * @access private
     * @var string
     */
    private $__content;
	
	/**
	 * Holds the unprocessed text block (code form).
	 *
	 * @access private
	 * @var string
	 */
	private $__contentRaw;

    /**
     * Counters used for looping.
     *
     * @access private
     * @var array
     */
    private $__counters = array(
        'spoiler' => 0
    );

    /**
     * List of options to apply to geshi output.
     *
     * @access private
     * @var array
     */
    private $__geshiConfig = array(
        'container' 	=> 'pre',
        'line_numbers'	=> false,
        'start_number' 	=> 1,
        'use_css'		=> false,
        'auto_casing'	=> false,
        'tab_width'		=> false,
        'strict_mode'	=> false
    );
	
	/**
	 * The last removed tag.
	 *
	 * @access private
	 * @var string
	 */
	private $__lastRemove;
	
    /**
     * Loads the string into the system, if no custom code it doesnt parse.
     *
     * @access public
     * @param string $string
     * @param array $allowed
     * @return void
     */
    public function __construct($string, array $allowed = array()) {
        $this->reset($string);
		
		if (!empty($allowed)) {
			$this->__allowed = array_unique($allowed);
		}

        // Include geshi
        if (file_exists(DECODA_GESHI .'geshi.php')) {
            include_once DECODA_GESHI .'geshi.php';
        } else {
			$this->configure('geshi', false);
        }
    }

    /**
     * Checks to see if the tag is allowed in the current parse.
     *
     * @access public
     * @param string $tag
     * @return boolean
     */
    public function allowed($tag) {
		if (empty($this->__allowed)) {
			return true;
		}
		
        return in_array($tag, $this->__allowed);
    }

    /**
     * Apply configuration.
     *
     * @access public
     * @param string $options
     * @param boolean $value
     * @return object
     */
    public function configure($options, $value = true) {
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                $this->configure($option, $value);
            }
        } else {
            if (!is_bool($value)) {
                return false;
            }

            if (isset($this->__config[$options])) {
                $this->__config[$options] = $value;
            }
        }
		
		return $this;
    }

    /**
     * Apply the geshi options.
     *
     * @access public
     * @param string $options
     * @param boolean $value
     * @return false
     */
    public function configureGeshi($options, $value = true) {
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                $this->configureGeshi($option, $value);
            }
        } else {
            if (isset($this->__geshiConfig[$options])) {
                $this->__geshiConfig[$options] = $value;
            }
        }
		
		return $this;
    }

    /**
     * Processes the string and translate all the markup code.
     *
     * @access public
     * @param boolean $return
     * @return string
     */
    public function parse($return = false) {
        if (!$this->__config['geshi']) {
            $this->__content = htmlentities($this->__content, ENT_NOQUOTES, 'UTF-8');
        }

        if (!$this->__config['parse']) {
            $this->__content = nl2br($this->__content);

        } else {
            // Replace standard markup
            $this->__content = nl2br(' '. $this->__content .' ');
			
			$markup = DecodaConfig::markup();
			$markupResult = DecodaConfig::markup(true);

            foreach ($markup as $tag => $pattern) {
                if ($this->allowed($tag)) {
                    $result = $markupResult[$tag];

                    if (!is_array($pattern)) {
                        $pattern = array($pattern);
                    }

                    foreach ($pattern as $pat) {
                        if (is_array($result)) {
                            $this->__content = preg_replace_callback($pat, array($this, $result[0]), $this->__content);
                        } else {
                            $this->__content = preg_replace($pat, $result, $this->__content);
                        }
                    }
                }
            }
		}
		
		// Make urls/emails clickable
		if ($this->__config['clickable']) {
			$this->__clickable();
		}

		// Convert smilies
		if ($this->__config['emoticons']) {
			$this->__emoticons();
		}

		// Censor words
		if ($this->__config['censor']) {
			$this->__censor();
		}

		// Clean linebreaks
		$this->__cleanup();

        if (!$return) {
            echo $this->__content;
        } else {
            return $this->__content;
        }
    }

    /**
     * Removes all decoda markup from a string.
     *
     * @access public
     * @param string $tag
     * @return object
     */
    public function removeCode($tag = 'p') {
		$markup = DecodaConfig::markup();
        
		if (isset($markup[$tag])) {
			if (!is_array($markup[$tag])) {
				$markup[$tag] = array($markup[$tag]);
			}
			
			foreach ($markup[$tag] as $pattern) {
				$this->__lastRemove = $tag;
				$this->__content = preg_replace_callback($pattern, array($this, '__remove'), $this->__content);
			}
		}
		
		return $this;
    }

    /**
     * Reset the parser to a new string.
     *
     * @access public
     * @param string $string
     * @return object
     */
    public function reset($string) {
        if ((strpos($string, '[') === false) && (strpos($string, ']') === false)) {
			$this->configure('parse', false);
        }
		
        $this->__content = $this->__contentRaw = $string;
		
		return $this;
    }

    /**
     * Parses the attributes into a string.
     *
     * @access protected
     * @param array $attributes
     * @return string
     */
    protected function _attributes(array $attributes) {
        $clean = array();

        if (!empty($attributes) && is_array($attributes)) {
            foreach ($attributes as $att => $value) {
                $clean[] = $att .'="'. $value .'"';
            }

            return ' '. implode(' ', $clean);
        }

        return;
    }
	
	/**
	 * Debug a variable.
	 *
	 * @access protected
	 * @param mixed $var
	 * @return object
	 */
	protected function _debug($var) {
		echo '<pre>'. print_r($var, true) .'</pre>';
		
		return $this;
	}
	
	/**
	 * Encrypt a string using an ASCII equivalent.
	 *
	 * @access protected
	 * @param string $string
	 * @return string
	 */
	protected function _encrypt($string) {
		$length = mb_strlen($string);
		$encrypted = '';
		
        for ($i = 0; $i < $length; ++$i) {
            $letter = mb_substr($string, $i, 1);
            $encrypted .= '&#' . ord($letter) . ';';
            
            unset($letter);
        }
		
		return $encrypted;
	}

    /**
     * Parses the text and censors words.
     *
     * @access private
     * @return void
     */
    private function __censor() {
		$censored = DecodaConfig::censored();
		
        if (!empty($censored) && is_array($censored)) {
            foreach ($censored as $word) {
                $word = trim(str_replace(array("\n", "\r"), '', $word));
                $this->__content = preg_replace_callback('/\s'. preg_quote($word, '/') .'/is', array($this, '__censorCallback'), $this->__content);
            }
        }
    }

    /**
     * Censors a blacklisted word and replaces with *.
     *
	 * @access private
     * @param array $matches
     * @return string
     */
    private function __censorCallback($matches) {
        $length = mb_strlen(trim($matches[0]));
        $censored = ' ';

        for ($i = 1; $i <= $length; ++$i) {
            $censored .= '*';
        }

        return $censored;
    }
	
    /**
     * Remove <br />s where they shouldn't be.
     *
     * @access private
     * @return string
     */
    private function __cleanup() {
		$string = $this->__content;
        $string = str_replace('</li><br />', '</li>', $string);
        $string = str_replace('<ul class="decoda-list"><br />', '<ul class="decoda-list">', $string);
        $string = str_replace('<ol class="decoda-olist"><br />', '<ol class="decoda-olist">', $string);

        $this->__content = trim($string);
    }

    /**
     * Makes links and emails clickable that dont have the [url] or [mail] tags.
     *
     * @access private
     * @return void
     */
    private function __clickable() {
        // Matches a link that begins with http(s)://, ftp(s)://, irc://
        if ($this->allowed('url')) {
			$protocol = '(http|ftp|irc|file|telnet)s?:\/?\/?';
			$login = '([-a-zA-Z0-9\.\+]+:[-a-zA-Z0-9\.\+]+@)?';
			$domain = '([-a-zA-Z0-9\.]{5,255}+)';
			$port = '(:[0-9]{0,6}+)?';
			$query = '([a-zA-Z0-9'. preg_quote('-_=;:&?/[]', '/') .']+)?';
            $this->__content = preg_replace_callback('/(^|\n|\s)'. $protocol . $login . $domain . $port . $query .'/is', array($this, '__urlCallback'), $this->__content);
        }
		
        // Matches an email@domain.tld
		// Based on schema http://en.wikipedia.org/wiki/Email_address
        if ($this->allowed('email')) {
            $this->__content = preg_replace_callback('/(^|\n|\s)([-a-zA-Z0-9\.\+!]{1,64}+)@([-a-zA-Z0-9\.]{5,255}+)/is', array($this, '__emailCallback'), $this->__content);
        }
    }

    /**
     * Preformat code so that it doesn't get converted.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __code($matches) {
        $attributes = array();

        if (!empty($matches[1])) {
            $attributes['lang'] = $matches[1];
        } else {
			// Escape because of GeSHi
            $matches[3] = preg_replace('/(&lt;br \/?&gt;)/is', '', htmlentities($matches[3], ENT_NOQUOTES, 'UTF-8'));
        }

        if (!empty($matches[2])) {
            $attributes['hl'] = $matches[2];
        }

        return '[decode'. $this->_attributes($attributes) .']'. base64_encode($matches[3]) .'[/decode]';
    }

    /**
     * Processes and replaces codeblocks / applies geshi if enabled.
     *
     * @access private
     * @param array $matches
     * @return $string
     */
    private function __decode($matches) {
        $language 	= !empty($matches[1]) ? mb_strtolower($matches[1]) : '';
        $highlight 	= !empty($matches[2]) ? explode(',', $matches[2]) : '';
		$code = preg_replace('/(<br \/?>)/is', '', base64_decode($matches[3]));

        if (empty($language) || !$this->__config['geshi']) {
            $codeBlock = '<pre class="decoda-code">'. $code .'</pre>';

        } else {
            $this->Geshi = new GeSHi($code, $language);
            $this->__geshi($highlight);
            $codeBlock = $this->Geshi->parse_code();

            if ($error = $this->Geshi->error()) {
                trigger_error('Decoda::__decode(): '. $error, E_USER_WARNING);
            }
        }

        return $codeBlock;
    }

    /**
     * Processes div tags and allows optional attributes.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __div($matches) {
        $textBlock = trim($matches[2]);
        $attributes = trim($matches[1]);
		$clean = array();
		
		if (!empty($attributes)) {
		
			// Urlencode so we don't explode on spaces within attributes
			$attributes = preg_replace_callback('/"(.*?)"/is', create_function(
				'$matches', 'return \'"\'. urlencode($matches[1]) .\'"\';'
			), $attributes);
			
			$attributes = explode(' ', $attributes);
		
			foreach ($attributes as $attr) {
				$parts = explode('=', $attr);
				$clean[$parts[0]] = htmlentities(urldecode(trim($parts[1], '"')), ENT_COMPAT, 'UTF-8');
			}
		}

        return '<div'. $this->_attributes($clean) .'>'. $textBlock .'</div>';
    }

    /**
     * Processes, obfuscates and replaces email tags.
     *
     * @access private
     * @param array $matches
     * @param bool $isCallback
     * @return string
     */
    private function __email($matches, $isCallback = false) {
        if ($isCallback) {
            $email = trim($matches[0]);
            $padding = $matches[1];
        } else {
            $email = trim($matches[1]);
            $emailText = isset($matches[2]) ? $matches[2] : '';
            $padding = '';
        }

        $encrypted = $this->_encrypt($email);
		
		if (empty($emailText)) {
			$emailText = $encrypted;
		}

        if ($this->__config['shorthand']) {
            return $padding .'[<a href="mailto:'. $encrypted .'">'. DecodaConfig::message('mail') .'</a>]';
        } else {
			return $padding .'<a href="mailto:'. $encrypted .'">'. $emailText .'</a>';
        }
    }

    /**
     * Callback for email processing.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __emailCallback($matches) {
        return $this->__email($matches, true);
    }

    /**
     * Convert smilies into images.
     *
     * @access private
     * @return void
     */
    private function __emoticons() {
		$emoticons = DecodaConfig::emoticons();
		
        if (!empty($emoticons)) {
            $path = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', DECODA_EMOTICONS);
            $path = str_replace(array('\\', '/'), '/', $path);

            foreach ($emoticons as $emoticon => $smilies) {
                foreach ($smilies as $smile) {
                    $image  = '$1<img src="'. $path . $emoticon .'.png" alt=""';
                    $image .= ($this->__config['xhtml']) ? ' />$2' : '>$2';

                    $this->__content = preg_replace('/(\s)'. preg_quote($smile, '/') .'(\s)?/is', $image, $this->__content);
                    unset($image);
                }
            }
        }
    }
	
    /**
     * Apply the custom settings to the geshi output.
     *
     * @access private
     * @param array $highlight
     * @return void
     */
    private function __geshi($highlight) {
        $options = $this->__geshiConfig;

        if (isset($this->Geshi)) {
            $this->Geshi->set_overall_style(null, false);
            $this->Geshi->set_encoding('UTF-8');
            $this->Geshi->set_overall_class('decoda-code');

            // Container
            switch ($options['container']) {
                case 'pre':     $container = GESHI_HEADER_PRE; break;
                case 'div':     $container = GESHI_HEADER_DIV; break;
                case 'table':   $container = GESHI_HEADER_PRE_TABLE; break;
                default:        $container = GESHI_HEADER_NONE; break;
            }

            $this->Geshi->set_header_type($container);

            // Line numbers
            if ($options['line_numbers'] == 'fancy') {
                $lineNumbers = GESHI_FANCY_LINE_NUMBERS;
            } else if ($options['line_numbers'] === true) {
                $lineNumbers = GESHI_NORMAL_LINE_NUMBERS;
            } else {
                $lineNumbers = GESHI_NO_LINE_NUMBERS;
            }

            $this->Geshi->enable_line_numbers($lineNumbers);

            if (is_numeric($options['start_number']) && $options['start_number'] >= 0) {
                $this->Geshi->start_line_numbers_at($options['start_number']);
            }

            // CSS / Spans
            if (is_bool($options['use_css'])) {
                $this->Geshi->enable_classes($options['use_css']);
            }

            // Auto-Casing
            switch ($options['auto_casing']) {
                case 'upper':   $casing = GESHI_CAPS_UPPER; break;
                case 'lower':   $casing = GESHI_CAPS_LOWER; break;
                default:        $casing = GESHI_CAPS_NO_CHANGE; break;
            }

            $this->Geshi->set_case_keywords($casing);

            // Tab width
            if ($options['container'] == GESHI_HEADER_DIV) {
                if (is_numeric($options['tab_width']) && $options['tab_width'] >= 0) {
                    $this->Geshi->set_tab_width($options['tab_width']);
                }
            }

            // Strict mode
            if (is_bool($options['strict_mode'])) {
                $this->Geshi->enable_strict_mode($options['strict_mode']);
            }

            // Highlight lines
            if (is_array($highlight)) {
                $this->Geshi->highlight_lines_extra($highlight);
            }
        }
    }

    /**
     * Processes img tags and allows optional attributes for width/height.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __img($matches) {
        $imgPath = trim($matches[3]);
        $width	 = trim($matches[1]);
        $height  = trim($matches[2]);
        $imgExt  = mb_strtolower(str_replace('.', '', mb_strrchr($imgPath, '.')));
        $attributes = array();

        // If the image extension is allowed
        if (in_array($imgExt, array('gif', 'jpg', 'jpeg', 'png'))) {
            $attributes['src'] = $imgPath;
            $attributes['alt'] = '';

            if (mb_substr($width, -1) == '%') {
                $width = trim($width, '%');
                $widthPercent = '%';
            } else {
                $widthPercent = '';
            }

            if (is_numeric($width) && $width > 0) {
                $attributes['width'] = $width . $widthPercent;
            }

            if (mb_substr($height, -1) == '%') {
                $height = str_replace('%', '', $height);
                $heightPercent = '%';
            } else {
                $heightPercent = '';
            }

            if (is_numeric($height) && $height > 0) {
                $attributes['height'] = $height . $heightPercent;
            }

            $imgStr  = '<img'. $this->_attributes($attributes);
            $imgStr .= ($this->__config['xhtml']) ? ' />' : '>';
        } else {
            $imgStr = $imgPath;
        }

        return $imgStr;
    }

    /**
     * Processes unordered lists.
     *
     * @access private
     * @param string $matches
     * @return string
     */
    private function __list($matches) {
		if (strpos($matches[0], 'olist') !== false) {
			$tag = 'ol';
			$class = 'decoda-olist';
		} else {
			$tag = 'ul';
			$class = 'decoda-list';
		}
		
        return '<'. $tag .' class="'. $class .'">'. str_replace("\n", '', $matches[1]) .'</'. $tag .'>';
    }

    /**
     * Processes and replaces nested quote tags.
     *
     * @access private
     * @param array $matches
     * @param boolean $parseChild
     * @return string
     */
    private function __quote($matches, $parseChild = true) {
        $markup = DecodaConfig::markup();
		$quote = '<blockquote class="decoda-quote">';

        if (isset($matches[1]) || isset($matches[2])) {
            $quote .= '<div class="decoda-quoteAuthor">';

            if (!empty($matches[2])) {
                $quote .= sprintf('<span class="decoda-quoteDate">%s</span>', date('m/d/Y h:i', strtotime($matches[2])));
            }

            if (!empty($matches[1])) {
                $quote .= 'Quote by '. $matches[1];
            }

            $quote .= '</div>';
        }

        $quote .= '<div class="decoda-quoteBody">';

        if ($this->__config['childQuotes'] && $parseChild) {
            $quote .= preg_replace_callback($markup['quote'], array($this, '__quoteInner'), $matches[3]);
        } else {
            $quote .= preg_replace($markup['quote'], '', $matches[3]);
        }

        $quote .= '</div></blockquote>';
        return $quote;
    }

    /**
     * Processes and replaces nested quote tags within quotes.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __quoteInner($matches) {
        return $this->__quote($matches, false);
    }
	
	/**
	 * Remove specific code and format specific tags.
	 * 
	 * @access private
	 * @param array $matches
	 * @return string
	 */
	private function __remove($matches) {
		if ($this->__lastRemove == 'video') {
			$content = $matches[1] .':'. $matches[3];
			
		} else if ($this->__lastRemove == 'email') {
			$content = $this->_encrypt($matches[1]);
		
		} else if (in_array($this->__lastRemove, array('code', 'decode', 'img', 'quote'))) {
			$content = $matches[3];
			
		} else if (in_array($this->__lastRemove, array('align', 'float', 'color', 'font', 'h16', 'size', 'div'))) {
			$content = $matches[2];
			
		} else {
			$content = $matches[1];
		}
		
		return $content;
	}

    /**
     * Show spoilers.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __spoiler($matches) {
        $id = $this->__counters['spoiler'];
		
		$showText = DecodaConfig::message('spoiler') .' ('. DecodaConfig::message('show') .')';
		$hideText = DecodaConfig::message('spoiler') .' ('. DecodaConfig::message('hide') .')';
		
		if ($this->__config['jquery']) {
			$click = "$('#spoilerContent-". $id ."').toggle(); $(this).html(($('#spoilerContent-". $id ."').is(':visible') ? '". $hideText ."' : '". $showText ."'));";
		} else {
			$click  = "document.getElementById('spoilerContent-". $id ."').style.display = (document.getElementById('spoilerContent-". $id ."').style.display == 'block' ? 'none' : 'block');";
			$click .= "this.innerHTML = (document.getElementById('spoilerContent-". $id ."').style.display == 'block' ? '". $hideText ."' : '". $showText ."');";
		}
		
        $html  = '<div class="decoda-spoiler" id="spoiler-'. $id .'">';
        $html .= '<button class="decoda-spoilerButton" type="button" onclick="'. $click .'">'. $showText .'</button>';
        $html .= '<div class="decoda-spoilerBody" id="spoilerContent-'. $id .'" style="display: none">'. $matches[1] .'</div>';
        $html .= '</div>';

        $this->__counters['spoiler']++;

        return $html;
    }

    /**
     * Processes and replaces URLs.
     *
     * @access private
     * @param array $matches
     * @param bool $isCallback
     * @return $string
     */
    private function __url($matches, $isCallback = false) {
        if ($isCallback) {
            $url = trim($matches[0]);
            $padding = $matches[1];
        } else {
            $url = trim($matches[1]);
            $urlText = isset($matches[2]) ? $matches[2] : '';
            $padding = '';
        }

		if (empty($urlText)) {
			$urlText = $url;
		}

        if ($this->__config['shorthand']) {
            return $padding .'[<a href="'. $url .'">'. DecodaConfig::message('link') .'</a>]';
        } else {
            return $padding .'<a href="'. $url .'">'. $urlText .'</a>';
        }
    }

    /**
     * Callback for url processing.
     *
     * @access private
     * @param array $matches
     * @return void
     */
    private function __urlCallback($matches) {
        return $this->__url($matches, true);
    }
	
	/**
	 * Return an embedded video if the video data exists.
	 *
	 * @access private
	 * @param array $matches
	 * @return string
	 */
	private function __video($matches) {
		$videos = DecodaConfig::videos();
		$site = !empty($matches[1]) ? $matches[1] : '';
		$size = !empty($matches[2]) ? $matches[2] : 'medium';
		$id = $matches[3];
		
		if (isset($videos[$site])) {
			$video = $videos[$site];
			$path = str_replace('{id}', $id, $video['path']);
			$size = isset($video[$size]) ? $video[$size] : $video['medium'];
			
			if ($video['player'] == 'embed') {
				return '<embed src="'. $path .'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'. $size[0] .'" height="'. $size[1] .'"></embed>';
			} else {
				return '<iframe src="'. $path .'" width="'. $size[0] .'" height="'. $size[1] .'" frameborder="0"></iframe>';
			}
		}
	}

}

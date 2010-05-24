<?php
/**
 * Decoda - Lightweight Markup Language
 *
 * Processes and translates custom markup (BB code style), functionality for word censoring, emoticons and GeSHi code highlighting.
 *
 * @author 		Miles Johnson - www.milesj.me
 * @copyright	Copyright 2006-2009, Miles Johnson, Inc.
 * @license 	http://www.opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		www.milesj.me/resources/script/decoda
 */

// Constants
define('DECODA', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('DECODA_GESHI', DECODA .'geshi'. DIRECTORY_SEPARATOR);
define('DECODA_EMOTICONS', DECODA .'emoticons'. DIRECTORY_SEPARATOR);
define('DECODA_CONFIG', DECODA .'config'. DIRECTORY_SEPARATOR);

// Includes
include DECODA .'config.php';

class Decoda {

    /**
     * Current version: www.milesj.me/resources/logs/decoda
     *
     * @access private
     * @var string
     */
    public $version = '2.9';

    /**
     * List of tags allowed to parse.
     *
     * @access private
     * @var array
     */
    private $__allowed = array();

    /**
     * Array of words to censor.
     *
     * @access private
     * @var array
     */
    private $__censored = array();

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
        'childQuotes'   => false
    );

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
     * Array of emoticons and smilies.
     *
     * @access private
     * @var array
     */
    private $__emoticons = array();

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
     * Default markup code.
     *
     * @access private
     * @var array
     */
    private $__markupCode = array(
        'code'      => '/\[code(?:\slang=\"([-_\sa-zA-Z0-9]+)\")?(?:\shl=\"([0-9,]+)\")?\](.*?)\[\/code\]/is',
        'b'         => '/\[b\](.*?)\[\/b\]/is',
        'i'         => '/\[i\](.*?)\[\/i\]/is',
        'u'         => '/\[u\](.*?)\[\/u\]/is',
        'align'     => '/\[align=(left|center|right)\](.*?)\[\/align\]/is',
        'float'     => '/\[float=(left|right)\](.*?)\[\/float\]/is',
        'color'     => '/\[color=(#[0-9a-fA-F]{6}|[a-z]+)\](.*?)\[\/color\]/is',
        'font'      => '/\[font=\"(.*?)\"\](.*?)\[\/font\]/is',
        'h16'       => '/\[h([1-6]{1})\](.*?)\[\/h([1-6]{1})\]/is',
        'size'      => '/\[size=((?:[1-2]{1})?[0-9]{1})\](.*?)\[\/size\]/is',
        'sub'       => '/\[sub\](.*?)\[\/sub\]/is',
        'sup'       => '/\[sup\](.*?)\[\/sup\]/is',
        'hide'      => '/\[hide\](.*?)\[\/hide\]/is',
        'img'       => '/\[img(?:\swidth=([0-9%]{1,4}+))?(?:\sheight=([0-9%]{1,4}+))?\]((?:ftp|http)s?:\/\/.*?)\[\/img\]/is',
        'div'       => '/\[div(?:\sid=\"([a-zA-Z0-9]+)\")?(?:\sclass=\"([a-zA-Z0-9\s]+)\")?\](.*?)\[\/div\]/is',
        'url'       => array(
            '/\[url\]((?:http|ftp|irc)s?:\/\/.*?)\[\/url\]/is',
            '/\[url=((?:http|ftp|irc)s?:\/\/.*?)\](.*?)\[\/url\]/is'
        ),
        'email'     => array(
            '/\[e?mail\](.*?)\[\/e?mail\]/is',
            '/\[e?mail=(.*?)\](.*?)\[\/e?mail\]/is'
        ),
        'quote'     => '/\[quote(?:=\"(.*?)\")?(?:\sdate=\"(.*?)\")?\](.*)\[\/quote\]/is',
        'list'      => '/\[list\](.*?)\[\/list\]/is',
        'spoiler'   => '/\[spoiler\](.*?)\[\/spoiler\]/is',
        'decode'    => '/\[decode(?:\slang=\"([-_\sa-zA-Z0-9]+)\")?(?:\shl=\"([0-9,]+)\")?\](.*)\[\/decode\]/is'
    );

    /**
     * Default markup result.
     *
     * @access private
     * @var array
     */
    private $__markupResult = array(
        'code'      => array('__callbackCode'),
        'b'         => '<b>$1</b>',
        'i'         => '<i>$1</i>',
        'u'         => '<u>$1</u>',
        'align'     => '<div style="text-align: $1">$2</div>',
        'float'     => '<div class="decoda-float-$1">$2</div>',
        'color'     => '<span style="color: $1">$2</span>',
        'font'      => '<span style="font-family: \'$1\', sans-serif;">$2</span>',
        'h16'       => '<h$1>$2</h$3>',
        'size'      => '<span style="font-size: $1px">$2</span>',
        'sub'       => '<sub>$1</sub>',
        'sup'       => '<sup>$1</sup>',
        'hide'      => '<span style="display: none">$1</span>',
        'img'       => array('__processImgs'),
        'div'       => array('__processDivs'),
        'url'       => array('__processUrls'),
        'email'     => array('__processEmails'),
        'quote'     => array('__processQuotes'),
        'list'      => array('__processLists'),
        'spoiler'   => array('__processSpoiler'),
        'decode'    => array('__processCode')
    );

    /**
     * Holds the block of text to be parsed.
     *
     * @access private
     * @var string
     */
    private $__textToParse;

    /**
     * Loads the string into the system, if no custom code it doesnt parse.
     *
     * @access public
     * @param string $string
     * @param array $allowed
     * @return void
     */
    public function __construct($string, $allowed = array()) {
        if ((strpos($string, '[') === false) && (strpos($string, ']') === false)) {
            $this->__config['parse'] = false;
        } else {
            if (!empty($allowed) && is_array($allowed)) {
                $this->__allowed = array_unique($allowed);
            }
        }

        // Include geshi
        if (file_exists(DECODA_GESHI .'geshi.php')) {
            require_once DECODA_GESHI .'geshi.php';
        } else {
            $this->__config['geshi'] = false;
        }

        // Load emoticons and censored
        $this->__emoticons      = DecodaConfig::emoticons();
        $this->__censored       = DecodaConfig::censored();
        $this->__textToParse    = $string;

        return false;
    }

    /**
     * Add censored words to the blacklist.
     *
     * @access public
     * @param array $censored
     * @return void
     */
    public function addCensored($censored = array()) {
        if (!empty($censored) && is_array($censored)) {
            $this->__censored = array_merge($this->__censored, $censored);
        }
    }

    /**
     * Add a custom emoticon.
     *
     * @access public
     * @param string $emoticon
     * @param array $smilies
     * @return void
     */
    public function addEmoticon($emoticon, $smilies = array()) {
        if (!is_array($smilies)) {
            $smilies = array($smilies);
        }

        if (isset($this->__emoticons[$emoticon])) {
            $this->__emoticons[$emoticon] = array_merge($this->__emoticons[$emoticon], $smilies);
        } else {
            $this->__emoticons[$emoticon] = $smilies;
        }
    }

    /**
     * Add custom code patterns to the mark up array.
     *
     * @access public
     * @param string $tag
     * @param string $pattern
     * @param string $replace
     * @return void
     */
    public function addMarkup($tag, $pattern, $replace) {
        $this->__markupCode[$tag] = $pattern;
        $this->__markupResult[$tag] = $replace;
    }

    /**
     * Checks to see if the tag is allowed in the current parse.
     *
     * @access public
     * @param string $tag
     * @return boolean
     */
    public function allowed($tag) {
        $allowed = array();

        if (!empty($this->__allowed) && is_array($this->__allowed)) {
            $allowed = $this->__allowed;
        }

        if (empty($allowed)) {
            return true;
        } else if (!in_array($tag, $allowed)) {
            return false;
        }

        return true;
    }

    /**
     * Apply configuration.
     *
     * @access public
     * @param string $options
     * @param bool $value
     * @return void
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
    }

    /**
     * Apply the geshi options.
     *
     * @access public
     * @param string $options
     * @param bool $value
     * @return false
     */
    public function configureGeshi($options, $value = true) {
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                $this->configureGeshi($option, $value);
            }
        } else {
            if (!is_bool($value)) {
                return false;
            }

            if (isset($this->__geshiConfig[$options])) {
                $this->__geshiConfig[$options] = $value;
            }
        }
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
            $this->__textToParse = htmlentities($this->__textToParse, ENT_NOQUOTES, 'UTF-8');
        }

        if (!$this->__config['parse']) {
            $string = nl2br($this->__textToParse);

        } else {
            // Replace standard markup
            $string = ' '. $this->__textToParse .' ';
            $string = nl2br($string);

            foreach ($this->__markupCode as $tag => $pattern) {
                if ($this->allowed($tag)) {
                    $result = $this->__markupResult[$tag];

                    if (!is_array($pattern)) {
                        $pattern = array($pattern);
                    }

                    foreach ($pattern as $pat) {
                        if (is_array($result)) {
                            $string = preg_replace_callback($pat, array($this, $result[0]), $string);
                        } else {
                            $string = preg_replace($pat, $result, $string);
                        }
                    }
                }
            }

            // Make urls/emails clickable
            if ($this->__config['clickable']) {
                $string = $this->__clickable($string);
            }

            // Convert smilies
            if ($this->__config['emoticons']) {
                $string = $this->__emoticons($string);
            }

            // Censor words
            if ($this->__config['censor']) {
                $string = $this->__censor($string);
            }

            // Clean linebreaks
            $string = $this->__cleanup($string);
        }

        if ($return === false) {
            echo $string;
        } else {
            return $string;
        }
    }

    /**
     * Removes all decoda markup from a string.
     *
     * @access public
     * @param string $tag
     * @param string $string
     * @return string
     * @static
     */
    public static function removeCode($tag = 'p', $string = null) {
        if (empty($string)) {
            $string = $this->__textToParse;
        }

        return preg_replace_callback('/\['. $tag .'\](.*?)\[\/'. $tag .'\]/is', create_function(
            '$matches', 'return $matches[1];'
        ), $string);
    }

    /**
     * Reset the parser to a new string.
     *
     * @access public
     * @param string $string
     * @return void
     */
    public function reset($string) {
        if ((strpos($string, '[') === false) && (strpos($string, ']') === false)) {
            $this->__config['parse'] = false;
        }

        $this->__textToParse = $string;
    }

    /**
     * Parses the attributes into a string.
     *
     * @access private
     * @param array $attributes
     * @return string
     */
    private function __attributes($attributes) {
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
     * Censors a blacklisted word and replaces with *.
     *
     * @param array $matches
     * @return string
     */
    private function __callbackCensored($matches) {
        $length = mb_strlen(trim($matches[0]));
        $censored = ' ';

        for ($i = 1; $i <= $length; ++$i) {
            $censored .= '*';
        }

        return $censored;
    }

    /**
     * Preformat code so that it doesn't get converted.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __callbackCode($matches) {
        $attributes = array();

        if (!empty($matches[1])) {
            $attributes['lang'] = $matches[1];
        } else {
            $matches[3] = preg_replace('/(&lt;br \/?&gt;)/is', '', htmlentities($matches[3], ENT_NOQUOTES, 'UTF-8'));
        }

        if (!empty($matches[2])) {
            $attributes['hl'] = $matches[2];
        }

        $return = '[decode'. $this->__attributes($attributes) .']'. base64_encode($matches[3]) .'[/decode]';
        return $return;
    }

    /**
     * Callback for email processing.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __callbackEmails($matches) {
        return $this->__processEmails($matches, true);
    }

    /**
     * Callback for url processing.
     *
     * @access private
     * @param array $matches
     * @return void
     */
    private function __callbackUrls($matches) {
        return $this->__processUrls($matches, true);
    }

    /**
     * Parses the text and censors words.
     *
     * @access private
     * @param string $string
     * @return string
     */
    private function __censor($string) {
        if (!empty($this->__censored) && is_array($this->__censored)) {
            foreach ($this->__censored as $word) {
                $word = trim(str_replace(array("\n", "\r"), '', $word));
                $string = preg_replace_callback('/\s'. preg_quote($word, '\\') .'/is', array($this, '__callbackCensored'), $string);
            }
        }

        return $string;
    }

    /**
     * Remove <br />s within [code] and [list].
     *
     * @access private
     * @param string $string
     * @return string
     */
    private function __cleanup($string) {
        $string = str_replace('</li><br />', '</li>', $string);
        $string = str_replace('<ul class="decoda-list"><br />', '<ul class="decoda-list">', $string);

        return trim($string);
    }

    /**
     * Makes links and emails clickable that dont have the [url] or [mail] tags.
     *
     * @access private
     * @param string $string
     * @return string
     */
    private function __clickable($string) {
        $string = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $string);

        // Matches a link that begins with http(s)://, ftp(s)://, irc://, www.
        if ($this->allowed('url')) {
            $string = preg_replace_callback("#(^|[\n ])(?:http|ftp|irc)s?:\/\/(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,4}(?:[-a-zA-Z0-9._\/&=+%?;\#]+)#is", array($this, '__callbackUrls'), $string);
            $string = preg_replace_callback("#(^|[\n ])www\.(?:[-a-zA-Z0-9._\/&=+;%?\#]+)#is", array($this, '__callbackUrls'), $string);
        }

        // Matches an email@domain.tld
        if ($this->allowed('email')) {
            $string = preg_replace_callback("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", array($this, '__callbackEmails'), $string);
        }

        return $string;
    }

    /**
     * Convert smilies into images.
     *
     * @access private
     * @param string $string
     * @return string
     */
    private function __emoticons($string) {
        if (!empty($this->__emoticons)) {
            $path = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', DECODA_EMOTICONS);
            $path = str_replace(array('\\', '/'), '/', $path);

            foreach ($this->__emoticons as $emoticon => $smilies) {
                foreach ($smilies as $smile) {
                    $image  = '$1<img src="'. $path . $emoticon .'.png" alt="" title=""';
                    $image .= ($this->__config['xhtml']) ? ' />$2' : '>$2';

                    $string = preg_replace('/(\s)'. preg_quote($smile, '/') .'(\s)?/is', $image, $string);
                    unset($image);
                }
            }
        }

        return $string;
    }

    /**
     * Processes and replaces codeblocks / applies geshi if enabled.
     *
     * @access private
     * @param array $matches
     * @return $string
     */
    private function __processCode($matches) {
        $language 	= !empty($matches[1]) ? mb_strtolower($matches[1]) : '';
        $highlight 	= !empty($matches[2]) ? explode(',', $matches[2]) : '';
        $code = preg_replace('/(<br \/?>)/is', '', base64_decode($matches[3]));

        if (empty($language) || $this->__config['geshi'] === false) {
            $codeBlock = '<pre class="decoda-code">'. $code .'</pre>';

        } else {
            $this->Geshi = new GeSHi($code, $language);
            $this->__processGeshi($highlight);
            $codeBlock = $this->Geshi->parse_code();

            if ($error = $this->Geshi->error()) {
                trigger_error('Decoda::__processCode(): '. $error, E_USER_WARNING);
            }
        }

        return $codeBlock;
    }

    /**
     * Processes div tags and allows optional attributes for id/class.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __processDivs($matches) {
        $textBlock 	= trim($matches[3]);
        $divId	  	= trim($matches[1]);
        $divClass 	= trim($matches[2]);
        $attributes = array();

        if (!empty($divId)) {
            $attributes['id'] = $divId;
        }

        if (!empty($divClass)) {
            $attributes['class'] = $divClass;
        }

        $div = '<div'. $this->__attributes($attributes) .'>'. $textBlock .'</div>';
        return $div;
    }

    /**
     * Processes, obfuscates and replaces email tags.
     *
     * @access private
     * @param array $matches
     * @param bool $isCallback
     * @return string
     */
    private function __processEmails($matches, $isCallback = false) {
        if ($isCallback === true) {
            $email = trim($matches[2]) .'@'. trim($matches[3]);
            $padding = $matches[1];
        } else {
            $email = trim($matches[1]);
            $emailText = isset($matches[2]) ? $matches[2] : '';
            $padding = '';
        }

        // Obfuscates the email using ASCII alternatives
        $encrypted = '';
        for ($i = 0; $i < mb_strlen($email); ++$i) {
            $letter = mb_substr($email, $i, 1);
            $encrypted .= '&#' . ord($letter) . ';';

            unset($letter, $encrypted);
        }

        if ($this->__config['shorthand']) {
            $emailStr = $padding .'[<a href="mailto:'. $encrypted .'" title="">mail</a>]';
        } else {
            $emailStr = $padding .'<a href="mailto:'. $encrypted .'" title="">'. (!empty($emailText) ? trim($emailText) : $encrypted) .'</a>';
        }

        return $emailStr;
    }

    /**
     * Apply the custom settings to the geshi output.
     *
     * @access private
     * @param array $highlight
     * @return void
     */
    private function __processGeshi($highlight) {
        $options = $this->__geshiConfig;

        if (isset($this->Geshi)) {
            $this->Geshi->set_overall_style(null, false);
            $this->Geshi->set_encoding('UTF-8');
            $this->Geshi->set_overall_class('decoda-code');

            // Container
            switch ($options['container']) {
                case 'pre':		$container = GESHI_HEADER_PRE; break;
                case 'div':		$container = GESHI_HEADER_DIV; break;
                case 'table':	$container = GESHI_HEADER_PRE_TABLE; break;
                default:		$container = GESHI_HEADER_NONE; break;
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
                case 'upper': $casing = GESHI_CAPS_UPPER; break;
                case 'lower': $casing = GESHI_CAPS_LOWER; break;
                default: 	  $casing = GESHI_CAPS_NO_CHANGE; break;
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
    private function __processImgs($matches) {
        $imgPath = trim($matches[3]);
        $width	 = trim($matches[1]);
        $height  = trim($matches[2]);
        $imgExt  = mb_strtolower(str_replace('.', '', mb_strrchr($imgPath, '.')));
        $attributes = array();

        // If the image extension is allowed
        if (in_array($imgExt, array('gif', 'jpg', 'jpeg', 'png', 'bmp'))) {
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

            $imgStr  = '<img'. $this->__attributes($attributes);
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
    private function __processLists($matches) {
        $list = '<ul class="decoda-list">';

        if (!empty($matches[1])) {
            $string = $matches[1];
            $string = str_replace("\n", '', $string);
            $string = preg_replace('/\[li\](.*?)\[\/li\]/is', '<li>$1</li>', $string);
            $list .= $string;
        }

        $list .= '</ul>';

        return $list;
    }

    /**
     * Processes and replaces nested quote tags.
     *
     * @access private
     * @param array $matches
     * @param boolean $parseChild
     * @return string
     */
    private function __processQuotes($matches, $parseChild = true) {
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
            $quote .= preg_replace_callback($this->__markupCode['quote'], array($this, '__processInnerQuotes'), $matches[3]);
        } else {
            $quote .= preg_replace($this->__markupCode['quote'], '', $matches[3]);
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
    private function __processInnerQuotes($matches) {
        return $this->__processQuotes($matches, false);
    }

    /**
     * Show spoilers.
     *
     * @access private
     * @param array $matches
     * @return string
     */
    private function __processSpoiler($matches) {
        $id = $this->__counters['spoiler'];
        $click = "document.getElementById('spoilerContent-". $id ."').style.display = (document.getElementById('spoilerContent-". $id ."').style.display == 'block' ? 'none' : 'block');";

        $html  = '<div class="decoda-spoiler" id="spoiler-'. $id .'">';
        $html .= '<button class="decoda-spoilerButton" type="button" onclick="'. $click .'">Spoiler: Show / Hide</button>';
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
    private function __processUrls($matches, $isCallback = false) {
        if ($isCallback === true) {
            $url = trim($matches[0]);
            $padding = $matches[1];
        } else {
            $url = trim($matches[1]);
            $urlText = isset($matches[2]) ? $matches[2] : '';
            $padding = '';
        }

        $urlText = !empty($urlText) ? $urlText : $url;
        $urlFull = (mb_substr($url, 0, 3) == 'www') ? 'http://'. $url : $url;

        if ($this->__config['shorthand']) {
            $urlStr = $padding .'[<a href="'. $urlFull .'" title="">link</a>]';
        } else {
            $urlStr = $padding .'<a href="'. $urlFull .'" title="">'. $urlText .'</a>';
        }

        return $urlStr;
    }

}

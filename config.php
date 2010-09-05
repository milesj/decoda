<?php
/**
 * Decoda - Configuration
 *
 * A configuration class to globally load emoticons, censored words and anything else.
 *
 * @author		Miles Johnson - www.milesj.me
 * @copyright	Copyright 2006-2010, Miles Johnson, Inc.
 * @license		http://www.opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		http://milesj.me/resources/script/decoda
 */

class DecodaConfig {

    /**
     * Array of words to censor.
     *
     * @access private
     * @var array
     * @static
     */
    private static $__censored = array();

    /**
     * Array of emoticons and smilies.
     *
     * @access private
     * @var array
     * @static
     */
    private static $__emoticons = array();

    /**
     * Default markup code.
     *
     * @access private
     * @var array
	 * @static
     */
    private static $__markupCode = array(
        'code'      => '/\[code(?:\slang=\"([-_\sa-zA-Z0-9]+)\")?(?:\shl=\"([0-9,]+)\")?\](.*?)\[\/code\]/is',
        'b'         => '/\[b\](.*?)\[\/b\]/is',
        'i'         => '/\[i\](.*?)\[\/i\]/is',
        'u'         => '/\[u\](.*?)\[\/u\]/is',
        's'         => '/\[s\](.*?)\[\/s\]/is',
        'align'     => '/\[align=(left|center|right|justify)\](.*?)\[\/align\]/is',
        'float'     => '/\[float=(left|right)\](.*?)\[\/float\]/is',
        'color'     => '/\[color=(#[0-9a-fA-F]{3,6}|[a-z]+)\](.*?)\[\/color\]/is',
        'font'      => '/\[font=\"(.*?)\"\](.*?)\[\/font\]/is',
        'h16'       => '/\[h([1-6]{1})\](.*?)\[\/h([1-6]{1})\]/is',
        'size'      => '/\[size=((?:[1-2]{1})?[0-9]{1})\](.*?)\[\/size\]/is',
        'sub'       => '/\[sub\](.*?)\[\/sub\]/is',
        'sup'       => '/\[sup\](.*?)\[\/sup\]/is',
        'var'       => '/\[var\](.*?)\[\/var\]/is',
        'hide'      => '/\[hide\](.*?)\[\/hide\]/is',
        'note'      => '/\[note\](.*?)\[\/note\]/is',
        'alert'		=> '/\[alert\](.*?)\[\/alert\]/is',
        'img'       => '/\[img(?:\swidth=([0-9%]{1,4}+))?(?:\sheight=([0-9%]{1,4}+))?\]((?:ftp|http)s?:\/\/.*?)\[\/img\]/is',
        'div'       => '/\[div(.*?)\](.*?)\[\/div\]/is',
        'url'       => array(
            '/\[url\]((?:http|ftp|irc|file|telnet)s?:\/\/.*?)\[\/url\]/is',
            '/\[url=((?:http|ftp|irc|file|telnet)s?:\/\/.*?)\](.*?)\[\/url\]/is'
        ),
        'email'     => array(
            '/\[e?mail\](.*?)\[\/e?mail\]/is',
            '/\[e?mail=(.*?)\](.*?)\[\/e?mail\]/is'
        ),
        'quote'     => '/\[quote(?:=\"(.*?)\")?(?:\sdate=\"(.*?)\")?\](.*?)\[\/quote\]/is',
        'olist'		=> '/\[olist\](.*?)\[\/olist\]/is',
        'list'      => '/\[list\](.*?)\[\/list\]/is',
		'li'		=> '/\[li\](.*?)\[\/li\]/is',
        'spoiler'   => '/\[spoiler\](.*?)\[\/spoiler\]/is',
        'video'     => '/\[video(?:=\"(.*?)\")?(?:\ssize=\"(.*?)\")?\](.*?)\[\/video\]/is',
        'decode'    => '/\[decode(?:\slang=\"([-_\sa-zA-Z0-9]+)\")?(?:\shl=\"([0-9,]+)\")?\](.*?)\[\/decode\]/is'
    );

    /**
     * Default markup result.
     *
     * @access private
     * @var array
	 * @static
     */
    private static $__markupResult = array(
        'code'      => array('__code'),
        'b'         => '<b>$1</b>',
        'i'         => '<i>$1</i>',
        'u'         => '<u>$1</u>',
        's'         => '<span style="text-decoration: line-through;">$1</span>',
        'align'     => '<div style="text-align: $1">$2</div>',
        'float'     => '<div class="decoda-float-$1">$2</div>',
        'color'     => '<span style="color: $1">$2</span>',
        'font'      => '<span style="font-family: \'$1\', sans-serif;">$2</span>',
        'h16'       => '<h$1>$2</h$3>',
        'size'      => '<span style="font-size: $1px">$2</span>',
        'sub'       => '<sub>$1</sub>',
        'sup'       => '<sup>$1</sup>',
        'var'       => '<var>$1</var>',
        'hide'      => '<span style="display: none">$1</span>',
        'note'		=> '<div class="decoda-note">$1</div>',
        'alert'     => '<div class="decoda-alert">$1</div>',
        'img'       => array('__img'),
        'div'       => array('__div'),
        'url'       => array('__url'),
        'email'     => array('__email'),
        'quote'     => array('__quote'),
        'olist'		=> array('__list'),
        'list'      => array('__list'),
		'li'		=> '<li>$1</li>',
        'spoiler'   => array('__spoiler'),
		'video'		=> array('__video'),
        'decode'    => array('__decode')
    );
	
	/**
	 * Message strings for localization purposes.
	 *
	 * @access private
	 * @var array
	 * @static
	 */
	private static $__messages = array(
		'spoiler'	=> 'Spoiler',
		'hide'		=> 'Hide',
		'show'		=> 'Show',
		'link'		=> 'link',
		'mail'		=> 'mail',
		'quoteBy'	=> 'Quote by {author}'
	);
	
	/**
	 * Video sizes and data.
	 * 
	 * @access private
	 * @var array
	 * @static
	 */
	private static $__videos = array(
		'youtube' => array(
			'small' => array(560, 340),
			'medium' => array(640, 385),
			'large' => array(853, 505),
			'player' => 'embed',
			'path' => 'http://youtube.com/v/{id}'
		),
		'vimeo' => array(
			'small' => array(400, 225),
			'medium' => array(550, 375),
			'large' => array(700, 525),
			'player' => 'iframe',
			'path' => 'http://player.vimeo.com/video/{id}'
		),
		'liveleak' => array(
			'small' => array(450, 370),
			'medium' => array(600, 520),
			'large' => array(750, 670),
			'player' => 'embed',
			'path' => 'http://liveleak.com/e/{id}'
		),
		'veoh' => array(
			'small' => array(410, 341),
			'medium' => array(610, 541),
			'large' => array(810, 741),
			'player' => 'embed',
			'path' => 'http://veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.3.1004&permalinkId={id}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous'
		),
		'dailymotion' => array(
			'small' => array(320, 256),
			'medium' => array(480, 384),
			'large' => array(560, 448),
			'player' => 'embed',
			'path' => 'http://dailymotion.com/swf/video/{id}&additionalInfos=0&autoPlay=0'
		),
		'myspace' => array(
			'small' => array(325, 260),
			'medium' => array(425, 360),
			'large' => array(525, 460),
			'player' => 'embed',
			'path' => 'http://mediaservices.myspace.com/services/media/embed.aspx/m={id},t=1,mt=video'
		)
	);
	
    /**
     * Add censored words to the blacklist.
     *
     * @access public
     * @param array $censored
     * @return void
	 * @static
     */
    public static function addCensor(array $censored) {
        if (!empty($censored)) {
            self::$__censored = $censored + self::$__censored;
        }
    }

    /**
     * Add a custom emoticon.
     *
     * @access public
     * @param string $emoticon
     * @param array $smilies
     * @return void
	 * @static
     */
    public static function addEmoticon($emoticon, array $smilies) {
        if (isset(self::$__emoticons[$emoticon])) {
            self::$__emoticons[$emoticon] = $smilies + self::$__emoticons[$emoticon];
        } else {
            self::$__emoticons[$emoticon] = $smilies;
        }
    }

    /**
     * Add custom code patterns to the mark up array. Does not support callbacks.
     *
     * @access public
     * @param string $tag
     * @param string $pattern
     * @param string $replace
     * @return void
	 * @static
     */
    public static function addMarkup($tag, $pattern, $replace) {
        self::$__markupCode[$tag] = $pattern;
        self::$__markupResult[$tag] = $replace;
    }
	
	/**
	 * Add a custom video handler.
	 *
	 * @access public
	 * @param string $site
	 * @param array $data
	 * @return void
	 */
	public static function addVideo($site, array $data) {
		if (!empty($data['path'])) {
			$data = $data + array(
				'player' => 'embed',
				'small' => array(560, 340),
				'medium' => array(640, 385),
				'large' => array(853, 505)
			);
			
			self::$__videos[$site] = $data;
		}
	}

    /**
     * Load the censored words from the text file.
     *
     * @access public
     * @return array
     * @static
     */
    public static function censored() {
        if (empty(self::$__censored)) {
            $path = DECODA_CONFIG .'censored.txt';

            if (file_exists($path)) {
                self::$__censored = file($path) + self::$__censored;
            }
        }
        
        return self::$__censored;
    }

    /**
     * Load the emoticons from the text file.
     *
     * @access public
     * @return array
     * @static
     */
    public static function emoticons() {
        if (empty(self::$__emoticons)) {
            $path = DECODA_CONFIG .'emoticons.txt';

            if (file_exists($path)) {
                $emoticons = file($path);

                foreach ($emoticons as $emo) {
                    list($key, $smilies) = explode('=', $emo);
                    self::$__emoticons[trim($key)] = explode(' ', trim($smilies));
                }
            }
        }

        return self::$__emoticons;
    }
	
	/**
	 * Return the markup regex patterns.
	 *
	 * @access public
	 * @param boolean $replacements
	 * @return array
	 * @static
	 */
	public static function markup($replacements = false) {
		if ($replacements) {
			return self::$__markupResult;
		} else {
			return self::$__markupCode;
		}
	}
	
	/**
	 * Return a message string if it exists.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 * @static
	 */
	public static function message($key) {
		return self::$__messages[$key] ? self::$__messages[$key] : '';
	}
	
	/**
	 * Update the locale message strings.
	 *
	 * @access public
	 * @param string|array $key
	 * @param string $message
	 * @return void
	 * @static
	 */
	public static function updateMessages($key, $message = '') {
		if (is_array($key)) {
			foreach ($key as $index => $message) {
				self::updateMessages($index, $message);
			}
		} else {
			self::$__messages[$key] = $message;
		}
	}
	
	/**
	 * Return the video format data.
	 *
	 * @access public
	 * @return array
	 */
	public static function videos() {
		return self::$__videos;
	}
    
}
<?php
/**
 * Decoda Configuration
 *
 * A configuration class to globally load emoticons, censored words and anything else.
 *
 * @author		Miles Johnson - http://milesj.me
 * @copyright	Copyright 2006-2011, Miles Johnson, Inc.
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		http://milesj.me/code/php/decoda
 */

class DecodaConfig {

    /**
     * Array of words to censor.
     *
     * @access protected
     * @var array
     * @static
     */
    protected static $_censored = array();

    /**
     * Array of emoticons and smilies.
     *
     * @access protected
     * @var array
     * @static
     */
    protected static $_emoticons = array();

	/**
	 * Message strings for localization purposes.
	 *
	 * @access protected
	 * @var array
	 * @static
	 */
	protected static $_messages = array(
		'en_US' => array(
			'spoiler'	=> 'Spoiler',
			'hide'		=> 'Hide',
			'show'		=> 'Show',
			'link'		=> 'link',
			'mail'		=> 'mail',
			'quoteBy'	=> 'Quote by {author}'
		)
	);
	
	/**
	 * Video sizes and data.
	 * 
	 * @access protected
	 * @var array
	 * @static
	 */
	protected static $_videos = array(
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
		self::$_censored = $censored + self::$_censored;
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
        if (isset(self::$_emoticons[$emoticon])) {
            self::$_emoticons[$emoticon] = $smilies + self::$_emoticons[$emoticon];
        } else {
            self::$_emoticons[$emoticon] = $smilies;
        }
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
			
			self::$_videos[$site] = $data;
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
        if (empty(self::$_censored)) {
            $path = DECODA_CONFIG .'censored.txt';

            if (file_exists($path)) {
                self::$_censored = file($path) + self::$_censored;
            }
        }
        
        return self::$_censored;
    }

    /**
     * Load the emoticons from the text file.
     *
     * @access public
     * @return array
     * @static
     */
    public static function emoticons() {
        if (empty(self::$_emoticons)) {
            $path = DECODA_CONFIG .'emoticons.txt';

            if (file_exists($path)) {
                $emoticons = file($path);

                foreach ($emoticons as $emo) {
                    list($key, $smilies) = explode('=', $emo);
                    self::$_emoticons[trim($key)] = explode(' ', trim($smilies));
                }
            }
        }

        return self::$_emoticons;
    }
	
	/**
	 * Return a message string if it exists.
	 *
	 * @access public
	 * @param string $key
	 * @return string
	 * @static
	 */
	public static function message($key, $locale = 'en_US') {
		if (isset(self::$_messages[$locale])) {
			return self::$_messages[$locale][$key] ? self::$_messages[$locale][$key] : '';
		}
		
		return;
	}
	
	/**
	 * Update the locale message strings.
	 *
	 * @access public
	 * @param string|array $key
	 * @param string $message
	 * @param string $locale
	 * @return void
	 * @static
	 */
	public static function translate($key, $message = '', $locale = 'en_US') {
		if (is_array($key)) {
			foreach ($key as $index => $message) {
				self::translate($index, $message, $locale);
			}
		} else {
			if (empty(self::$_messages[$locale])) {
				self::$_messages[$locale] = self::$_messages['en_US'];
			}
			
			self::$_messages[$locale][$key] = $message;
		}
	}
	
	/**
	 * Return the video format data.
	 *
	 * @access public
	 * @return array
	 */
	public static function videos() {
		return self::$_videos;
	}
    
}
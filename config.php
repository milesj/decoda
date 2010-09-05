<?php
/**
 * Decoda - Configuration
 *
 * A configuration class to globally load emoticons and censored words.
 *
 * @author 		Miles Johnson - www.milesj.me
 * @copyright	Copyright 2006-2009, Miles Johnson, Inc.
 * @license 	http://www.opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link		www.milesj.me/resources/script/decoda
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
	 * Video sizes and data.
	 * 
	 * @access private
	 * @var array
	 * @static
	 */
	private static $__videoData = array(
		'youtube' => array(
			'small' => array(560, 340),
			'medium' => array(640, 385),
			'large' => array(853, 505),
			'player' => 'embed',
			'path' => 'http://youtube.com/v/:id'
		),
		'vimeo' => array(
			'small' => array(400, 225),
			'medium' => array(550, 375),
			'large' => array(700, 525),
			'player' => 'iframe',
			'path' => 'http://player.vimeo.com/video/:id'
		),
		'liveleak' => array(
			'small' => array(450, 370),
			'medium' => array(600, 520),
			'large' => array(750, 670),
			'player' => 'embed',
			'path' => 'http://liveleak.com/e/:id'
		),
		'veoh' => array(
			'small' => array(410, 341),
			'medium' => array(610, 541),
			'large' => array(810, 741),
			'player' => 'embed',
			'path' => 'http://veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.3.1004&permalinkId=:id&player=videodetailsembedded&videoAutoPlay=0&id=anonymous'
		),
		'dailymotion' => array(
			'small' => array(320, 256),
			'medium' => array(480, 384),
			'large' => array(560, 448),
			'player' => 'embed',
			'path' => 'http://dailymotion.com/swf/video/:id&additionalInfos=0&autoPlay=0'
		),
		'myspace' => array(
			'small' => array(325, 260),
			'medium' => array(425, 360),
			'large' => array(525, 460),
			'player' => 'embed',
			'path' => 'http://mediaservices.myspace.com/services/media/embed.aspx/m=:id,t=1,mt=video'
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
    public static function addCensored(array $censored) {
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
        if (isset($this->__emoticons[$emoticon])) {
            self::$__emoticons[$emoticon] = $smilies + self::$__emoticons[$emoticon];
        } else {
            self::$__emoticons[$emoticon] = $smilies;
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
			
			self::$__videoData[$site] = $data;
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
	 * Return the video data.
	 *
	 * @access public
	 * @return array
	 */
	public static function videoData() {
		return self::$__videoData;
	}
    
}
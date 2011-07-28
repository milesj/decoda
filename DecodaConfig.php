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
     * List of words to censore.
     *
     * @access protected
     * @var array
     * @static
     */
    protected static $_censored = array();

    /**
     * Mapping of emoticons and smilies.
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
		'en-us' => array(
			'spoiler'	=> 'Spoiler',
			'hide'		=> 'Hide',
			'show'		=> 'Show',
			'link'		=> 'link',
			'mail'		=> 'mail',
			'quoteBy'	=> 'Quote by {author}'
		)
	);

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
                self::$_censored = array_map('trim', file($path));
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
            $path = DECODA_CONFIG .'emoticons.json';

            if (file_exists($path)) {
				self::$_emoticons = json_decode(file_get_contents($path), true);
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
	public static function message($key, $locale = 'en-us') {
		return isset(self::$_messages[$locale][$key]) ? self::$_messages[$locale][$key] : '';
	}
	
	/**
	 * Update the locale message strings.
	 *
	 * @access public
	 * @param string $locale
	 * @param string|array $key
	 * @param string $message
	 * @return void
	 * @static
	 */
	public static function translate($locale, $key, $message = '') {
		if (is_array($key)) {
			foreach ($key as $index => $message) {
				self::translate($index, $message, $locale);
			}
		} else {
			if (empty(self::$_messages[$locale])) {
				self::$_messages[$locale] = self::$_messages['en-us'];
			}
			
			self::$_messages[$locale][$key] = $message;
		}
	}
    
}
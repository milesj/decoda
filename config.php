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
                self::$__censored = file($path);
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
    
}
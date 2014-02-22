<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Hook\AbstractHook;

class TestHook extends AbstractHook {

    /**
     * MD5 the string for testing.
     *
     * @param string $string
     * @return string
     */
    public function beforeParse($string) {
        return md5($string);
    }

    /**
     * MD5 the string for testing.
     *
     * @param string $string
     * @return string
     */
    public function afterParse($string) {
        return md5($string);
    }

}
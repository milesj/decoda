<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Test\Hook;

use Decoda\Hook\EmoticonHook;

class EmoticonHookBC extends EmoticonHook {

    protected function _emoticonCallback($matches) {
        $smiley = trim($matches[0]);

        if (count($matches) === 1 || !$this->hasSmiley($smiley)) {
            return $matches[0];
        }

        $l = isset($matches['left']) ? $matches['left'] : '';
        $r = isset($matches['right']) ? $matches['right'] : '';

        return $l . $this->render($smiley, $this->getParser()->getConfig('xhtmlOutput')) . $r;
    }
}

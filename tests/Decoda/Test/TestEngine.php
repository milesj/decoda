<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Engine\AbstractEngine;

class TestEngine extends AbstractEngine {

    /**
     * Render a template.
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function render(array $tag, $content) {
        return '';
    }

}
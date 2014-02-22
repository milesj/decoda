<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Component\AbstractComponent;
use Decoda\Hook;

/**
 * A hook allows you to inject functionality during certain events in the parsing cycle.
 */
abstract class AbstractHook extends AbstractComponent implements Hook {

    /**
     * Process the content after the parsing has finished.
     *
     * @param string $content
     * @return string
     */
    public function afterParse($content) {
        return $content;
    }

    /**
     * Process the content after the stripping has finished.
     *
     * @param string $content
     * @return string
     */
    public function afterStrip($content) {
        return $content;
    }

    /**
     * Process the content before the parsing begins.
     *
     * @param string $content
     * @return string
     */
    public function beforeParse($content) {
        return $content;
    }

    /**
     * Process the content before the stripping begins.
     *
     * @param string $content
     * @return string
     */
    public function beforeStrip($content) {
        return $content;
    }

    /**
     * Start up the Hook by initializing or loading any data before parsing begins.
     *
     * @return void
     */
    public function startup() {
        return;
    }

    /**
     * Add any filter dependencies.
     *
     * @param \Decoda\Decoda $decoda
     * @return \Decoda\Hook
     */
    public function setupFilters(Decoda $decoda) {
        return $this;
    }

}
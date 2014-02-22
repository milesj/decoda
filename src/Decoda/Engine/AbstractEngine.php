<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Component\AbstractComponent;
use Decoda\Filter;
use Decoda\Engine;

/**
 * Provides default methods for engines.
 */
abstract class AbstractEngine extends AbstractComponent implements Engine {

    /**
     * Lookup paths.
     *
     * @type array
     */
    protected $_paths = array();

    /**
     * Current filter.
     *
     * @type \Decoda\Filter
     */
    protected $_filter;

    /**
     * Add a template lookup path.
     *
     * @param string $path
     * @return \Decoda\Engine
     */
    public function addPath($path) {
        if (substr($path, -1) !== '/') {
            $path .= '/';
        }

        $this->_paths[] = $path;

        return $this;
    }

    /**
     * Return the current filter.
     *
     * @return \Decoda\Filter
     */
    public function getFilter() {
        return $this->_filter;
    }

    /**
     * Returns the paths to the templates.
     *
     * @return array
     */
    public function getPaths() {
        return $this->_paths;
    }

    /**
     * Sets the current filter.
     *
     * @param \Decoda\Filter $filter
     * @return \Decoda\Engine
     */
    public function setFilter(Filter $filter) {
        $this->_filter = $filter;

        return $this;
    }

}

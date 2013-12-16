<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for URLs.
 */
class UrlFilter extends AbstractFilter {

    /**
     * Configuration.
     *
     * @type array
     */
    protected $_config = array(
        'protocols' => array('http', 'https', 'ftp', 'irc', 'telnet')
    );
    
    /**
     * Default protocol
     */
    protected $_defaultProtocol = 'http';

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'url' => array(
            'htmlTag' => 'a',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => array(
                'default' => true
            ),
            'mapAttributes' => array(
                'default' => 'href'
            )
        ),
        'link' => array(
            'htmlTag' => 'a',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => array(
                'default' => true
            ),
            'mapAttributes' => array(
                'default' => 'href'
            )
        )
    );
    
    /**
     * Set default protocol 
     *
     * @param string $protocol
     * @return boolean
     */
    public function setDefaultProtocol($protocol)
    {
        if(!preg_match('/^(' . implode('|', $this->getConfig('protocols')) . ')/i', $protocol ))
        {
            return false;
        }
        
        $this->_defaultProtocol = strtolower($protocol);
        return true;
    }

    /**
     * Using shorthand variation if enabled.
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function parse(array $tag, $content) {
        $url = isset($tag['attributes']['href']) ? $tag['attributes']['href'] : $content;
        $protocols = $this->getConfig('protocols');

        $hasProtocol = preg_match('/^(' . implode('|', $protocols) . ')/i', $url);
        $url = (!$hasProtocol && filter_var($this->_defaultProtocol.'://'.$url, FILTER_VALIDATE_URL))?
                $this->_defaultProtocol.'://'.$url :
                $url;

        // Return an invalid URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $tag['attributes']['href'] = $url;

        if ($this->getParser()->getConfig('shorthandLinks')) {
            $tag['content'] = $this->message('link');

            return '[' . parent::parse($tag, $content) . ']';
        }

        return parent::parse($tag, $content);
    }

    /**
     * Strip a node but keep the URL regardless of location.
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function strip(array $tag, $content) {
        $url = isset($tag['attributes']['href']) ? $tag['attributes']['href'] : $content;

        return parent::strip($tag, $url);
    }

}

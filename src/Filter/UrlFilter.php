<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
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
     * @var array
     */
    protected $_config = [
        'protocols' => ['http', 'https', 'ftp', 'irc', 'telnet', 'mailto'],
        'defaultProtocol' => 'http'
    ];

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'url' => [
            'htmlTag' => 'a',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => true,
                'target' => '/^(?:blank|parent|top)$/'
            ],
            'mapAttributes' => [
                'default' => 'href'
            ]
        ],
        'link' => [
            'aliasFor' => 'url'
        ]
    ];

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
        $defaultProtocol = $this->getConfig('defaultProtocol');
        $hasProtocol = preg_match('/^(' . implode('|', $protocols) . ')/i', $url);

        if (!in_array($defaultProtocol, $protocols, true)) {
            $defaultProtocol = 'http';
        }

        // Allow relative and absolute paths, else check protocols
        if (!preg_match('/^(\.\.?)?\//', $url)) {
            if (!$hasProtocol) {
                // Only allow if no protocol exists, just not the ones not in the list
                if (preg_match('/^(?![a-z]+:\/\/)/', $url) && filter_var($defaultProtocol . '://' . $url, FILTER_VALIDATE_URL)) {
                    $url = $defaultProtocol . '://' . $url;
                } else {
                    return $url;
                }
            }

            // Return an invalid URL
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }

        $tag['attributes']['href'] = $url;

        if (!empty($tag['attributes']['target'])) {
            $tag['attributes']['target'] = '_' . $tag['attributes']['target'];
        }

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

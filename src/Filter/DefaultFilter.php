<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;
use \DateTime;

/**
 * Provides tags for basic font styling.
 */
class DefaultFilter extends AbstractFilter {

    /**
     * Configuration.
     *
     * @type array
     */
    protected $_config = [
        'timeFormat' => 'D, M jS Y, H:i'
    ];

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = [
        'b' => [
            'htmlTag' => ['b', 'strong'],
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'i' => [
            'htmlTag' => ['i', 'em'],
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'u' => [
            'htmlTag' => 'u',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        's' => [
            'htmlTag' => 'del',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'sub' => [
            'htmlTag' => 'sub',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'sup' => [
            'htmlTag' => 'sup',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'abbr' => [
            'htmlTag' => 'abbr',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => AbstractFilter::ALNUM
            ],
            'mapAttributes' => [
                'default' => 'title'
            ]
        ],
        'br' => [
            'htmlTag' => 'br',
            'autoClose' => true,
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_NONE
        ],
        'hr' => [
            'htmlTag' => 'hr',
            'autoClose' => true,
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE
        ],
        'time' => [
            'htmlTag' => 'time',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_NONE
        ]
    ];

    /**
     * Parse the timestamps for the time tag.
     *
     * @param array $tag
     * @param string $content
     * @return array
     */
    public function time(array $tag, $content) {
        $timestamp = is_numeric($content) ? $content : strtotime($content);

        $content = date($this->getConfig('timeFormat'), $timestamp);

        $tag['attributes']['datetime'] = date(DateTime::ISO8601, $timestamp);

        return [$tag, $content];
    }

}
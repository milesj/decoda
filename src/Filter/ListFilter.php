<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for ordered and unordered lists.
 */
class ListFilter extends AbstractFilter {

    const LIST_TYPE = '/^[-a-z]+$/i';

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'olist' => [
            'htmlTag' => 'ol',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'lineBreaks' => Decoda::NL_REMOVE,
            'childrenWhitelist' => ['li', '*'],
            'onlyTags' => true,
            'attributes' => [
                'default' => [self::LIST_TYPE, 'type-{default}']
            ],
            'mapAttributes' => [
                'default' => 'class'
            ],
            'htmlAttributes' => [
                'class' => 'decoda-olist'
            ]
        ],
        'ol' => [
            'aliasFor' => 'olist'
        ],
        'list' => [
            'htmlTag' => 'ul',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'lineBreaks' => Decoda::NL_REMOVE,
            'childrenWhitelist' => ['li', '*'],
            'onlyTags' => true,
            'attributes' => [
                'default' => [self::LIST_TYPE, 'type-{default}']
            ],
            'mapAttributes' => [
                'default' => 'class'
            ],
            'htmlAttributes' => [
                'class' => 'decoda-list'
            ]
        ],
        'ul' => [
            'aliasFor' => 'list'
        ],
        'li' => [
            'htmlTag' => 'li',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => ['olist', 'list', 'ol', 'ul']
        ],
        '*' => [
            'htmlTag' => 'li',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'childrenBlacklist' => ['olist', 'list', 'ol', 'ul', 'li'],
            'parent' => ['olist', 'list', 'ol', 'ul']
        ]
    ];

}

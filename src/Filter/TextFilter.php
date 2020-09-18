<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for text and font styling.
 */
class TextFilter extends AbstractFilter {

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'font' => [
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'escapeAttributes' => false,
            'attributes' => [
                'default' => ['/^[a-z0-9\-\s,\.\']+$/i', 'font-family: {default}']
            ],
            'mapAttributes' => [
                'default' => 'style'
            ]
        ],
        'size' => [
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => ['/^[1-2]{1}[0-9]{1}$/', 'font-size: {default}px'],
            ],
            'mapAttributes' => [
                'default' => 'style'
            ]
        ],
        'color' => [
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => ['/^(?:#[0-9a-f]{3,6}|[a-z]+)$/i', 'color: {default}'],
            ],
            'mapAttributes' => [
                'default' => 'style'
            ]
        ],
        'h1' => [
            'htmlTag' => 'h1',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'h2' => [
            'htmlTag' => 'h2',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'h3' => [
            'htmlTag' => 'h3',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'h4' => [
            'htmlTag' => 'h4',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'h5' => [
            'htmlTag' => 'h5',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'h6' => [
            'htmlTag' => 'h6',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ]
    ];

}

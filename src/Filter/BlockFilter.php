<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for block styled elements.
 */
class BlockFilter extends AbstractFilter {

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'align' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'attributes' => [
                'default' => ['/^(?:left|center|right|justify)$/i', 'align-{default}']
            ],
            'mapAttributes' => [
                'default' => 'class'
            ]
        ],
        'left' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'align-left'
            ]
        ],
        'right' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'align-right'
            ]
        ],
        'center' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'align-center'
            ]
        ],
        'justify' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'align-justify'
            ]
        ],
        'float' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'attributes' => [
                'default' => ['/^(?:left|right|none)$/i', 'float-{default}']
            ],
            'mapAttributes' => [
                'default' => 'class'
            ]
        ],
        'hide' => [
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'style' => 'display: none'
            ],
            'stripContent' => true
        ],
        'alert' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'decoda-alert'
            ],
            'stripContent' => true
        ],
        'note' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'htmlAttributes' => [
                'class' => 'decoda-note'
            ],
            'stripContent' => true
        ],
        'div' => [
            'htmlTag' => 'div',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'attributes' => [
                'default' => self::ALPHA,
                'class' => self::ALNUM
            ],
            'mapAttributes' => [
                'default' => 'id'
            ],
            'stripContent' => true
        ],
        'spoiler' => [
            'template' => 'spoiler',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'stripContent' => true
        ]
    ];

}

<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Test;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;

class TestFilter extends AbstractFilter {

    /**
     * Example tags.
     *
     * @type array
     */
    protected $_tags = [
        'example' => [
            'htmlTag' => 'example',
            'displayType' => Decoda::TYPE_BLOCK
        ],
        'template' => [
            'template' => 'test'
        ],
        'templateMissing' => [
            'template' => 'test_missing'
        ],

        // Inline and block nesting
        'inline' => [
            'htmlTag' => 'inline',
            'displayType' => Decoda::TYPE_INLINE
        ],
        'inlineAllowInline' => [
            'htmlTag' => 'inlineAllowInline',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'inlineAllowBlock' => [
            'htmlTag' => 'inlineAllowBlock',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ],
        'inlineAllowBoth' => [
            'htmlTag' => 'inlineAllowBoth',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_BOTH
        ],
        'block' => [
            'htmlTag' => 'block',
            'displayType' => Decoda::TYPE_BLOCK
        ],
        'blockAllowInline' => [
            'htmlTag' => 'blockAllowInline',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'blockAllowBlock' => [
            'htmlTag' => 'blockAllowBlock',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ],
        'blockAllowBoth' => [
            'htmlTag' => 'blockAllowBoth',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH
        ],

        // Attribute testing
        'attributes' => [
            'htmlTag' => 'attributes',
            'displayType' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => self::WILDCARD,
                'alpha' => self::ALPHA,
                'alnum' => self::ALNUM,
                'numeric' => self::NUMERIC,
                'under_score' => self::WILDCARD,
                'dash-ed' => self::WILDCARD
            ],
            'mapAttributes' => [
                'default' => 'wildcard',
                'a' => 'alpha',
                'n' => 'numeric',
                'u_s' => 'under_score',
                'd-e' => 'dash-ed'
            ],
            'htmlAttributes' => [
                'id' => 'custom-html'
            ],
            'escapeAttributes' => true
        ],

        //CamelCase Tag with attributes testing
        'fooBar' => [
            'htmlTag' => 'span',
            'displayType' => Decoda::TYPE_INLINE,
            'attributes' => [
                'default' => self::WILDCARD,
            ],
            'mapAttributes' => [
                'default' => 'class'
            ]
        ],

        // Parent child hierarchy
        'parent' => [
            'htmlTag' => 'parent',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ],
        'parentNoPersist' => [
            'htmlTag' => 'parentNoPersist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'persistContent' => false
        ],
        'parentWhitelist' => [
            'htmlTag' => 'parentWhitelist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'onlyTags' => true,
            'childrenWhitelist' => ['whiteChild']
        ],
        'parentBlacklist' => [
            'htmlTag' => 'parentBlacklist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'onlyTags' => true,
            'childrenBlacklist' => ['whiteChild']
        ],
        'whiteChild' => [
            'htmlTag' => 'whiteChild',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'parent' => ['parent', 'parentWhitelist', 'parentBlacklist']
        ],
        'blackChild' => [
            'htmlTag' => 'blackChild',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'parent' => ['parent', 'parentWhitelist', 'parentBlacklist']
        ],
        'depth' => [
            'htmlTag' => 'depth',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'maxChildDepth' => 2,
            'persistContent' => false
        ],

        // CRLF formatting
        'lineBreaksRemove' => [
            'htmlTag' => 'lineBreaksRemove',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE
        ],
        'lineBreaksPreserve' => [
            'htmlTag' => 'lineBreaksPreserve',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_PRESERVE
        ],
        'lineBreaksConvert' => [
            'htmlTag' => 'lineBreaksConvert',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_CONVERT
        ],

        // Content pattern matching
        'pattern' => [
            'htmlTag' => 'pattern',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'contentPattern' => '/^[a-z]+@[a-z]+$/i',
            'attributes' => [
                'default' => self::WILDCARD
            ],
            'mapAttributes' => [
                'default' => 'attr'
            ]
        ],

        // Self closing HTML tag
        'autoClose' => [
            'htmlTag' => 'autoClose',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'autoClose' => true,
            'attributes' => [
                'foo' => self::WILDCARD,
                'bar' => self::WILDCARD
            ]
        ],

        // Aliasing
        'aliasBase' => [
            'htmlTag' => 'aliasBase',
            'displayType' => Decoda::TYPE_BLOCK,
            'attributes' => [
                'foo' => self::WILDCARD,
                'bar' => self::WILDCARD
            ]
        ],
        'aliased' => [
            'aliasFor' => 'aliasBase',
            'attributes' => [
                'baz' => self::NUMERIC
            ]
        ]
    ];

}
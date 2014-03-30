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
    protected $_tags = array(
        'example' => array(
            'htmlTag' => 'example',
            'displayType' => Decoda::TYPE_BLOCK
        ),
        'template' => array(
            'template' => 'test'
        ),
        'templateMissing' => array(
            'template' => 'test_missing'
        ),

        // Inline and block nesting
        'inline' => array(
            'htmlTag' => 'inline',
            'displayType' => Decoda::TYPE_INLINE
        ),
        'inlineAllowInline' => array(
            'htmlTag' => 'inlineAllowInline',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'inlineAllowBlock' => array(
            'htmlTag' => 'inlineAllowBlock',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ),
        'inlineAllowBoth' => array(
            'htmlTag' => 'inlineAllowBoth',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_BOTH
        ),
        'block' => array(
            'htmlTag' => 'block',
            'displayType' => Decoda::TYPE_BLOCK
        ),
        'blockAllowInline' => array(
            'htmlTag' => 'blockAllowInline',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_INLINE
        ),
        'blockAllowBlock' => array(
            'htmlTag' => 'blockAllowBlock',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ),
        'blockAllowBoth' => array(
            'htmlTag' => 'blockAllowBoth',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH
        ),

        // Attribute testing
        'attributes' => array(
            'htmlTag' => 'attributes',
            'displayType' => Decoda::TYPE_INLINE,
            'attributes' => array(
                'default' => self::WILDCARD,
                'alpha' => self::ALPHA,
                'alnum' => self::ALNUM,
                'numeric' => self::NUMERIC
            ),
            'mapAttributes' => array(
                'default' => 'wildcard',
                'a' => 'alpha',
                'n' => 'numeric'
            ),
            'htmlAttributes' => array(
                'id' => 'custom-html'
            ),
            'escapeAttributes' => true
        ),

        // Parent child hierarchy
        'parent' => array(
            'htmlTag' => 'parent',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK
        ),
        'parentNoPersist' => array(
            'htmlTag' => 'parentNoPersist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'persistContent' => false
        ),
        'parentWhitelist' => array(
            'htmlTag' => 'parentWhitelist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'onlyTags' => true,
            'childrenWhitelist' => array('whiteChild')
        ),
        'parentBlacklist' => array(
            'htmlTag' => 'parentBlacklist',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'onlyTags' => true,
            'childrenBlacklist' => array('whiteChild')
        ),
        'whiteChild' => array(
            'htmlTag' => 'whiteChild',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
        ),
        'blackChild' => array(
            'htmlTag' => 'blackChild',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
        ),
        'depth' => array(
            'htmlTag' => 'depth',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'maxChildDepth' => 2,
            'persistContent' => false
        ),

        // CRLF formatting
        'lineBreaksRemove' => array(
            'htmlTag' => 'lineBreaksRemove',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE
        ),
        'lineBreaksPreserve' => array(
            'htmlTag' => 'lineBreaksPreserve',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_PRESERVE
        ),
        'lineBreaksConvert' => array(
            'htmlTag' => 'lineBreaksConvert',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_CONVERT
        ),

        // Content pattern matching
        'pattern' => array(
            'htmlTag' => 'pattern',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'contentPattern' => '/^[a-z]+@[a-z]+$/i',
            'attributes' => array(
                'default' => self::WILDCARD
            ),
            'mapAttributes' => array(
                'default' => 'attr'
            )
        ),

        // Self closing HTML tag
        'autoClose' => array(
            'htmlTag' => 'autoClose',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'autoClose' => true,
            'attributes' => array(
                'foo' => self::WILDCARD,
                'bar' => self::WILDCARD
            )
        ),

        // Aliasing
        'aliasBase' => array(
            'htmlTag' => 'aliasBase',
            'displayType' => Decoda::TYPE_BLOCK,
            'attributes' => array(
                'foo' => self::WILDCARD,
                'bar' => self::WILDCARD
            )
        ),
        'aliased' => array(
            'aliasFor' => 'aliasBase',
            'attributes' => array(
                'baz' => self::NUMERIC
            )
        )
    );

}
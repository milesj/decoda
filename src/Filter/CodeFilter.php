<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;
use Decoda\Hook\CodeHook;

/**
 * Provides tags for code block and variable elements.
 */
class CodeFilter extends AbstractFilter {

    /**
     * Configuration.
     *
     * @type array
     */
    protected $_config = [
        'classPrefix' => 'lang-',
        'highlightAttribute' => 'data-line'
    ];

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = [
        'code' => [
            'template' => 'code',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'lineBreaks' => Decoda::NL_PRESERVE,
            'preserveTags' => true,
            'attributes' => [
                'default' => self::ALPHA,
                'hl' => self::NUMERIC
            ],
            'mapAttributes' => [
                'default' => 'lang'
            ],
            'stripContent' => true
        ],
        'source' => [
            'htmlTag' => 'code',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ],
        'var' => [
            'htmlTag' => 'var',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE
        ]
    ];

    /**
     * Add any hook dependencies.
     *
     * @param \Decoda\Decoda $decoda
     * @return \Decoda\Filter\CodeFilter
     */
    public function setupHooks(Decoda $decoda) {
        $decoda->addHook(new CodeHook());

        return $this;
    }

}
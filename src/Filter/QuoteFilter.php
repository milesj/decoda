<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides the tag for quoting users and blocks of texts.
 */
class QuoteFilter extends AbstractFilter {

    /**
     * Configuration.
     *
     * @var array
     */
    protected $_config = [
        'dateFormat' => 'M jS Y, H:i:s'
    ];

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'quote' => [
            'template' => 'quote',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'attributes' => [
                'default' => self::WILDCARD,
                'date' => self::WILDCARD
            ],
            'mapAttributes' => [
                'default' => 'author'
            ],
            'maxChildDepth' => 2,
            'persistContent' => false,
            'stripContent' => true
        ]
    ];

}

<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for tables, rows and cells.
 */
class TableFilter extends AbstractFilter {

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'table' => [
            'htmlTag' => 'table',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => ['tr', 'row', 'thead', 'tbody', 'tfoot'],
            'attributes' => [
                'class' => self::ALNUM
            ],
            'htmlAttributes' => [
                'class' => 'decoda-table'
            ]
        ],
        'thead' => [
            'htmlTag' => 'thead',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => ['tr', 'row'],
            'parent' => ['table']
        ],
        'tbody' => [
            'htmlTag' => 'tbody',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => ['tr', 'row'],
            'parent' => ['table']
        ],
        'tfoot' => [
            'htmlTag' => 'tfoot',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => ['tr', 'row'],
            'parent' => ['table']
        ],
        'tr' => [
            'htmlTag' => 'tr',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => ['td', 'th', 'col'],
            'parent' => ['table', 'thead', 'tbody', 'tfoot']
        ],
        'row' => [
            'aliasFor' => 'tr'
        ],
        'td' => [
            'htmlTag' => 'td',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => ['tr', 'row'],
            'attributes' => [
                'default' => self::NUMERIC,
                'cols' => self::NUMERIC,
                'rows' => self::NUMERIC
            ],
            'aliasAttributes' => [
                'colspan' => 'cols',
                'rowspan' => 'rows'
            ],
            'mapAttributes' => [
                'default' => 'colspan',
                'cols' => 'colspan',
                'rows' => 'rowspan'
            ]
        ],
        'col' => [
            'aliasFor' => 'td'
        ],
        'th' => [
            'htmlTag' => 'th',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => ['tr', 'row'],
            'attributes' => [
                'default' => self::NUMERIC,
                'cols' => self::NUMERIC,
                'rows' => self::NUMERIC
            ],
            'aliasAttributes' => [
                'colspan' => 'cols',
                'rowspan' => 'rows'
            ],
            'mapAttributes' => [
                'default' => 'colspan',
                'cols' => 'colspan',
                'rows' => 'rowspan'
            ]
        ]
    ];

}

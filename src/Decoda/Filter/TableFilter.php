<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
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
     * @type array
     */
    protected $_tags = array(
        'table' => array(
            'htmlTag' => 'table',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => array('tr', 'thead', 'tbody', 'tfoot'),
            'attributes' => array(
                'class' => self::ALNUM
            ),
            'htmlAttributes' => array(
                'class' => 'decoda-table'
            )
        ),
        'thead' => array(
            'htmlTag' => 'thead',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => array('tr'),
            'parent' => array('table')
        ),
        'tbody' => array(
            'htmlTag' => 'tbody',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => array('tr'),
            'parent' => array('table')
        ),
        'tfoot' => array(
            'htmlTag' => 'tfoot',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => array('tr'),
            'parent' => array('table')
        ),
        'tr' => array(
            'htmlTag' => 'tr',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'lineBreaks' => Decoda::NL_REMOVE,
            'onlyTags' => true,
            'childrenWhitelist' => array('td', 'th'),
            'parent' => array('table', 'thead', 'tbody', 'tfoot')
        ),
        'td' => array(
            'htmlTag' => 'td',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => array('tr'),
            'attributes' => array(
                'default' => self::NUMERIC
            ),
            'mapAttributes' => array(
                'default' => 'colspan'
            )
        ),
        'th' => array(
            'htmlTag' => 'th',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'parent' => array('tr'),
            'attributes' => array(
                'default' => self::NUMERIC
            ),
            'mapAttributes' => array(
                'default' => 'colspan'
            )
        )
    );

}
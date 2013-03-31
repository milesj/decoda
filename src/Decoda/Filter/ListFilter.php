<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
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
	protected $_tags = array(
		'olist' => array(
			'htmlTag' => 'ol',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'lineBreaks' => Decoda::NL_REMOVE,
			'childrenWhitelist' => array('li'),
			'attributes' => array(
				'default' => array(self::LIST_TYPE, 'type-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			),
			'htmlAttributes' => array(
				'class' => 'decoda-olist'
			)
		),
		'list' => array(
			'htmlTag' => 'ul',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'lineBreaks' => Decoda::NL_REMOVE,
			'childrenWhitelist' => array('li'),
			'attributes' => array(
				'default' => array(self::LIST_TYPE, 'type-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			),
			'htmlAttributes' => array(
				'class' => 'decoda-list'
			)
		),
		'li' => array(
			'htmlTag' => 'li',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'parent' => array('olist', 'list')
		)
	);

}
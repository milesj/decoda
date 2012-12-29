<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\FilterAbstract;

/**
 * Provides tags for ordered and unordered lists.
 *
 * @package	mjohnson.decoda.filters
 */
class ListFilter extends FilterAbstract {

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'olist' => array(
			'htmlTag' => 'ol',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'lineBreaks' => Decoda::NL_REMOVE,
			'childrenWhitelist' => array('li'),
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
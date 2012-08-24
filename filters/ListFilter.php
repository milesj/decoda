<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

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
			'tag' => 'ol',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'lineBreaks' => self::NL_REMOVE,
			'children' => array('li'),
			'html' => array(
				'class' => 'decoda-olist'
			)
		),
		'list' => array(
			'tag' => 'ul',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'lineBreaks' => self::NL_REMOVE,
			'children' => array('li'),
			'html' => array(
				'class' => 'decoda-list'
			)
		),
		'li' => array(
			'tag' => 'li',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'parent' => array('olist', 'list')
		)
	);

}
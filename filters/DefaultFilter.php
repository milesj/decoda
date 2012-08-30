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
 * Provides tags for basic font styling.
 *
 * @package	mjohnson.decoda.filters
 */
class DefaultFilter extends FilterAbstract {

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'b' => array(
			'tag' => array('b', 'strong'),
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		'i' => array(
			'tag' => array('i', 'em'),
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		'u' => array(
			'tag' => 'u',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		's' => array(
			'tag' => 'del',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		'sub' => array(
			'tag' => 'sub',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		'sup' => array(
			'tag' => 'sup',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		)
	);

}
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
 * Provides tags for block styled elements.
 *
 * @package	mjohnson.decoda.filters
 */
class BlockFilter extends FilterAbstract {

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'align' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/^(?:left|center|right|justify)$/i', 'align-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			)
		),
		'left' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-left'
			)
		),
		'right' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-right'
			)
		),
		'center' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-center'
			)
		),
		'justify' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-justify'
			)
		),
		'float' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/^(?:left|right|none)$/i', 'float-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			)
		),
		'hide' => array(
			'tag' => 'span',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'style' => 'display: none'
			)
		),
		'alert' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'decoda-alert'
			)
		),
		'note' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'decoda-note'
			)
		),
		'div' => array(
			'tag' => 'div',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => '/^[-_a-z0-9]+$/i',
				'class' => '/^[-_a-z0-9\s]+$/i'
			),
			'mapAttributes' => array(
				'default' => 'id'
			)
		),
		'spoiler' => array(
			'template' => 'spoiler',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH
		)
	);

}
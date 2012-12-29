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
 * Provides tags for block styled elements.
 *
 * @package	mjohnson.decoda.filters
 */
class BlockFilter extends FilterAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'spoilerToggle' => "$('#spoiler-content-{id}').toggle();"
	);

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'align' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/^(?:left|center|right|justify)$/i', 'align-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			)
		),
		'left' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-left'
			)
		),
		'right' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-right'
			)
		),
		'center' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-center'
			)
		),
		'justify' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'align-justify'
			)
		),
		'float' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/^(?:left|right|none)$/i', 'float-{default}')
			),
			'mapAttributes' => array(
				'default' => 'class'
			)
		),
		'hide' => array(
			'htmlTag' => 'span',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'style' => 'display: none'
			),
			'stripContent' => true
		),
		'alert' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'decoda-alert'
			),
			'stripContent' => true
		),
		'note' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'htmlAttributes' => array(
				'class' => 'decoda-note'
			),
			'stripContent' => true
		),
		'div' => array(
			'htmlTag' => 'div',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'attributes' => array(
				'default' => self::ALPHA,
				'class' => self::ALNUM
			),
			'mapAttributes' => array(
				'default' => 'id'
			),
			'stripContent' => true
		),
		'spoiler' => array(
			'template' => 'spoiler',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'stripContent' => true
		)
	);

}
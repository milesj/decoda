<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests;

use mjohnson\decoda\filters\FilterAbstract;

class TestFilter extends FilterAbstract {

	/**
	 * Example tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'example' => array(
			'htmlTag' => 'example',
			'displayType' => self::TYPE_BLOCK
		),
		'template' => array(
			'template' => 'test'
		),
		'templateMissing' => array(
			'template' => 'test_missing'
		),

		// Inline and block nesting
		'inline' => array(
			'htmlTag' => 'inline',
			'displayType' => self::TYPE_INLINE
		),
		'inlineAllowInline' => array(
			'htmlTag' => 'inlineAllowInline',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_INLINE
		),
		'inlineAllowBlock' => array(
			'htmlTag' => 'inlineAllowBlock',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_BLOCK
		),
		'inlineAllowBoth' => array(
			'htmlTag' => 'inlineAllowBoth',
			'displayType' => self::TYPE_INLINE,
			'allowedTypes' => self::TYPE_BOTH
		),
		'block' => array(
			'htmlTag' => 'block',
			'displayType' => self::TYPE_BLOCK
		),
		'blockAllowInline' => array(
			'htmlTag' => 'blockAllowInline',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_INLINE
		),
		'blockAllowBlock' => array(
			'htmlTag' => 'blockAllowBlock',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK
		),
		'blockAllowBoth' => array(
			'htmlTag' => 'blockAllowBoth',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BOTH
		),

		// Attribute testing
		'attributes' => array(
			'htmlTag' => 'attributes',
			'displayType' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => self::WILDCARD,
				'alpha' => self::ALPHA,
				'alnum' => self::ALNUM,
				'numeric' => self::NUMERIC
			),
			'mapAttributes' => array(
				'default' => 'wildcard'
			),
			'htmlAttributes' => array(
				'id' => 'custom-html'
			),
			'escapeAttributes' => true
		),

		// Parent child hierarchy
		'parent' => array(
			'htmlTag' => 'parent',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK
		),
		'parentNoPersist' => array(
			'htmlTag' => 'parentNoPersist',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'persistContent' => false
		),
		'parentWhitelist' => array(
			'htmlTag' => 'parentWhitelist',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'childrenWhitelist' => array('whiteChild')
		),
		'parentBlacklist' => array(
			'htmlTag' => 'parentBlacklist',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'childrenBlacklist' => array('whiteChild')
		),
		'whiteChild' => array(
			'htmlTag' => 'whiteChild',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
		),
		'blackChild' => array(
			'htmlTag' => 'blackChild',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
		),
		'depth' => array(
			'htmlTag' => 'depth',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'maxChildDepth' => 2,
			'persistContent' => false
		),

		// CRLF formatting
		'lineBreaksRemove' => array(
			'htmlTag' => 'lineBreaksRemove',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'lineBreaks' => self::NL_REMOVE
		),
		'lineBreaksPreserve' => array(
			'htmlTag' => 'lineBreaksPreserve',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'lineBreaks' => self::NL_PRESERVE
		),
		'lineBreaksConvert' => array(
			'htmlTag' => 'lineBreaksConvert',
			'displayType' => self::TYPE_BLOCK,
			'allowedTypes' => self::TYPE_BLOCK,
			'lineBreaks' => self::NL_CONVERT
		),
	);

}
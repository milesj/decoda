<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests;

use mjohnson\decoda\Decoda;
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
			'displayType' => Decoda::TYPE_BLOCK
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
			'displayType' => Decoda::TYPE_INLINE
		),
		'inlineAllowInline' => array(
			'htmlTag' => 'inlineAllowInline',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE
		),
		'inlineAllowBlock' => array(
			'htmlTag' => 'inlineAllowBlock',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_BLOCK
		),
		'inlineAllowBoth' => array(
			'htmlTag' => 'inlineAllowBoth',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_BOTH
		),
		'block' => array(
			'htmlTag' => 'block',
			'displayType' => Decoda::TYPE_BLOCK
		),
		'blockAllowInline' => array(
			'htmlTag' => 'blockAllowInline',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_INLINE
		),
		'blockAllowBlock' => array(
			'htmlTag' => 'blockAllowBlock',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK
		),
		'blockAllowBoth' => array(
			'htmlTag' => 'blockAllowBoth',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH
		),

		// Attribute testing
		'attributes' => array(
			'htmlTag' => 'attributes',
			'displayType' => Decoda::TYPE_INLINE,
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
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK
		),
		'parentNoPersist' => array(
			'htmlTag' => 'parentNoPersist',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'persistContent' => false
		),
		'parentWhitelist' => array(
			'htmlTag' => 'parentWhitelist',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'childrenWhitelist' => array('whiteChild')
		),
		'parentBlacklist' => array(
			'htmlTag' => 'parentBlacklist',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'childrenBlacklist' => array('whiteChild')
		),
		'whiteChild' => array(
			'htmlTag' => 'whiteChild',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
		),
		'blackChild' => array(
			'htmlTag' => 'blackChild',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'parent' => array('parent', 'parentWhitelist', 'parentBlacklist')
		),
		'depth' => array(
			'htmlTag' => 'depth',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'maxChildDepth' => 2,
			'persistContent' => false
		),

		// CRLF formatting
		'lineBreaksRemove' => array(
			'htmlTag' => 'lineBreaksRemove',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'lineBreaks' => Decoda::NL_REMOVE
		),
		'lineBreaksPreserve' => array(
			'htmlTag' => 'lineBreaksPreserve',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'lineBreaks' => Decoda::NL_PRESERVE
		),
		'lineBreaksConvert' => array(
			'htmlTag' => 'lineBreaksConvert',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'lineBreaks' => Decoda::NL_CONVERT
		),

		// Content pattern matching
		'pattern' => array(
			'htmlTag' => 'pattern',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'contentPattern' => '/^[a-z]+@[a-z]+$/i',
			'attributes' => array(
				'default' => self::WILDCARD
			),
			'mapAttributes' => array(
				'default' => 'attr'
			)
		),

		// Self closing HTML tag
		'autoClose' => array(
			'htmlTag' => 'autoClose',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BLOCK,
			'autoClose' => true,
			'attributes' => array(
				'foo' => self::WILDCARD,
				'bar' => self::WILDCARD
			)
		),
	);

}
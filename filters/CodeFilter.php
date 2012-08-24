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
use mjohnson\decoda\hooks\CodeHook;

/**
 * Provides tags for code block and variable elements.
 *
 * @package	mjohnson.decoda.filters
 */
class CodeFilter extends FilterAbstract {

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'code' => array(
			'template' => 'code',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'lineBreaks' => self::NL_PRESERVE,
			'preserveTags' => true,
			'attributes' => array(
				'default' => '/[a-z0-9]+/i',
				'hl' => '/[0-9,]+/'
			)
		),
		'var' => array(
			'tag' => 'code',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		)
	);

	/**
	 * Add any hook dependencies.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $decoda
	 * @return \mjohnson\decoda\filters\CodeFilter
	 */
	public function setupHooks(Decoda $decoda) {
		$decoda->addHook(new CodeHook());

		return $this;
	}

}
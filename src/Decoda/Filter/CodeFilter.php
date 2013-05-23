<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;
use Decoda\Hook\CodeHook;

/**
 * Provides tags for code block and variable elements.
 */
class CodeFilter extends AbstractFilter {

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'classPrefix' => 'lang-',
		'highlightAttribute' => 'data-line'
	);

	/**
	 * Supported tags.
	 *
	 * @var array
	 */
	protected $_tags = array(
		'code' => array(
			'template' => 'code',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'lineBreaks' => Decoda::NL_PRESERVE,
			'preserveTags' => true,
			'attributes' => array(
				'default' => self::ALPHA,
				'hl' => self::NUMERIC
			),
			'mapAttributes' => array(
				'default' => 'lang'
			),
			'stripContent' => true
		),
		'source' => array(
			'htmlTag' => 'code',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE
		),
		'var' => array(
			'htmlTag' => 'var',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE
		)
	);

	/**
	 * Add any hook dependencies.
	 *
	 * @param \Decoda\Decoda $decoda
	 * @return \Decoda\Filter\CodeFilter
	 */
	public function setupHooks(Decoda $decoda) {
		$decoda->addHook(new CodeHook());

		return $this;
	}

}
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
			'tag' => 'example',
			'displayType' => self::TYPE_INLINE,
			'htmlAttributes' => array(
				'class' => 'example'
			)
		)
	);

}
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
 * Provides the tag for quoting users and blocks of texts.
 *
 * @package	mjohnson.decoda.filters
 */
class QuoteFilter extends FilterAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'dateFormat' => 'M jS Y, H:i:s'
	);

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'quote' => array(
			'template' => 'quote',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_BOTH,
			'attributes' => array(
				'default' => self::WILDCARD,
				'date' => self::WILDCARD
			),
			'mapAttributes' => array(
				'default' => 'author'
			),
			'maxChildDepth' => 2,
			'persistContent' => false,
			'stripContent' => true
		)
	);

}
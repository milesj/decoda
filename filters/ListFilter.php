<?php

class ListFilter extends DecodaFilter {

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
			'lineBreaks' => false,
			'allowed' => array('li')
		),
		'list' => array(
			'tag' => 'ul',
			'type' => self::TYPE_BLOCK,
			'lineBreaks' => false,
			'allowed' => array('li')
		),
		'li' => array(
			'tag' => 'li',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'parent' => array('olist', 'list')
		)
	);
	
}
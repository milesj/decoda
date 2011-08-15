<?php

class QuoteFilter extends DecodaFilter {
	
	/**
	 * Configuration.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_config = array('childDepth' => 2);
	
	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'quote' => array(
			'template' => 'quote',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => '(.*?)',
				'date' => '(.*?)'
			),
			'map' => array(
				'default' => 'author'
			)
		)
	);
	
}
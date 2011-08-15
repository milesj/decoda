<?php

class CodeFilter extends DecodaFilter {

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
			'lineBreaks' => false,
			'preserve' => true,
			'escape' => true,
			'attributes' => array(
				'lang' => '([a-zA-Z0-9]+)',
				'hl' => '([0-9,]+)'
			)
		),
		'var' => array(
			'tag' => 'var',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		)
	);
	
}
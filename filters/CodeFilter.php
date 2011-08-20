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
			'preserveTags' => true,
			'escapeContent' => true,
			'attributes' => array(
				'default' => '/[a-zA-Z0-9]+/i',
				'hl' => '/[0-9,]+/'
			)
		),
		'var' => array(
			'tag' => 'code',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		)
	);

}
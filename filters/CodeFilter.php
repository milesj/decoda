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
			'attributes' => array(
				'lang' => '([-_\sa-zA-Z0-9]+)',
				'hl' => '([0-9,]+)'
			)
		),
		'var' => array(
			'tag' => 'var',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		)
	);

	/**
	 * Use the content as the image source.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		return parent::parse($tag, htmlentities($content, ENT_NOQUOTES, 'UTF-8'));
	}
	
}
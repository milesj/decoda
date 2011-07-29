<?php

class ImageFilter extends DecodaFilter {

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'img' => array(
			'tag' => 'img',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_NONE,
			'selfClose' => true,
			'attributes' => array(
				'width' => '([0-9%]{1,4}+)',
				'height' => '([0-9%]{1,4}+)'
			)
		),
		'image' => array(
			'tag' => 'img',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_NONE,
			'selfClose' => true,
			'attributes' => array(
				'width' => '([0-9%]{1,4}+)',
				'height' => '([0-9%]{1,4}+)'
			)
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
		$tag['attributes']['src'] = $content;
		$tag['attributes']['alt'] = '';
		
		return parent::parse($tag, $content);
	}
	
}
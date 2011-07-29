<?php

class TextFilter extends DecodaFilter {

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'font' => array(
			'tag' => 'span',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '(.*?)'
			),
			'format' => 'style="font-family: {default}, sans-serif;"'
		),
		'size' => array(
			'tag' => 'span',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '((?:[1-2]{1})?[0-9]{1})',
			),
			'format' => 'style="font-size: {default}px"'
		),
		'color' => array(
			'tag' => 'span',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '(#[0-9a-fA-F]{3,6}|[a-z]+)'
			),
			'format' => 'style="color: {default}"'
		),
		'h1' => array(
			'tag' => 'h1',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		),
		'h2' => array(
			'tag' => 'h2',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		),
		'h3' => array(
			'tag' => 'h3',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		),
		'h4' => array(
			'tag' => 'h4',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		),
		'h5' => array(
			'tag' => 'h5',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		),
		'h6' => array(
			'tag' => 'h6',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_INLINE
		)
	);
	
}
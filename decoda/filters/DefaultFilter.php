<?php

class DefaultFilter extends DecodaFilter {

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'b' => array(
			'tag' => array('b', 'strong'),
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		),
		'i' => array(
			'tag' => array('i', 'em'),
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		),
		'u' => array(
			'tag' => 'u',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		),
		's' => array(
			'tag' => 'del',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		),
		'sub' => array(
			'tag' => 'sub',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		),
		'sup' => array(
			'tag' => 'sup',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE
		)
	);

}
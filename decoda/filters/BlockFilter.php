<?php

class BlockFilter extends DecodaFilter {

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'align' => array(   
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/left|center|right|justify/i', 'align-{default}')
			),
			'map' => array(
				'default' => 'class'
			)
		),
		'float' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => array('/left|right|none/i', 'float-{default}')
			),
			'map' => array(
				'default' => 'class'
			)
		),
		'hide' => array(
			'tag' => 'span',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'html' => array(
				'style' => 'display: none'
			)
		),
		'alert' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'html' => array(
				'class' => 'decoda-alert'
			)
		),
		'note' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'html' => array(
				'class' => 'decoda-note'
			)
		),
		'div' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'id' => '/[-_a-z0-9]+/i',
				'class' => '/[-_a-z0-9\s]+/' 
			)
		),
		'spoiler' => array(
			'template' => 'spoiler',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH
		)
	);

}
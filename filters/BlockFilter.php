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
				'default' => '(left|center|right|justify)'
			),
			'format' => 'class="align-{default}"'
		),
		'float' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'default' => '(left|right|none)'
			),
			'format' => 'class="float-{default}"'
		),
		'hide' => array(
			'tag' => 'span',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'format' => 'style="display: none"'
		),
		'alert' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'format' => 'class="decoda-alert"'
		),
		'note' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'format' => 'class="decoda-note"'
		),
		'div' => array(
			'tag' => 'div',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH,
			'attributes' => array(
				'id' => '([-_a-zA-Z0-9]+)',
				'class' => '([-_a-zA-Z0-9\s]+)' 
			)
		),
		'spoiler' => array(
			'template' => 'spoiler',
			'type' => self::TYPE_BLOCK,
			'allowed' => self::TYPE_BOTH
		)
	);

}
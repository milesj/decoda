<?php

class BlockFilter extends DecodaFilter {

	protected $_tags = array(  
		'align' => array(   
			'tag' => 'div',
			'type' => 'block',
			'allowed' => 'all',
			'attributes' => array(
				'default' => '(left|center|right|justify)'
			),
			'format' => 'class="align-{default}"'
		),
		'float' => array(
			'tag' => 'div',
			'type' => 'block',
			'allowed' => 'all',
			'attributes' => array(
				'default' => '(left|right|none)'
			),
			'format' => 'class="float-{default}"'
		),
		'hide' => array(
			'tag' => 'span',
			'type' => 'block',
			'allowed' => 'all',
			'format' => 'style="display: none"'
		),
		'alert' => array(
			'tag' => 'div',
			'type' => 'block',
			'allowed' => 'all',
			'format' => 'class="decoda-alert"'
		),
		'note' => array(
			'tag' => 'div',
			'type' => 'block',
			'allowed' => 'all',
			'format' => 'class="decoda-note"'
		),
		'div' => array(
			'tag' => array('div'),
			'type' => 'block',
			'allowed' => 'all'
		),
		'spoiler' => array(
			'tag' => array('spoiler'),
			'type' => 'block',
			'allowed' => 'all'
		)
	);
	
}
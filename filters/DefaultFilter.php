<?php

class DefaultFilter extends DecodaFilter {

	protected $_tags = array(  
		'b' => array(
			'tag' => 'b',
			'type' => 'inline',
			'allowed' => 'inline'
		),
		'i' => array(
			'tag' => 'i',
			'type' => 'inline',
			'allowed' => 'inline'
		),
		'u' => array(
			'tag' => 'u',
			'type' => 'inline',
			'allowed' => 'inline'
		),
		's' => array(
			'tag' => 'del',
			'type' => 'inline',
			'allowed' => 'inline'
		),
		'sub' => array(
			'tag' => 'sub',
			'type' => 'inline',
			'allowed' => 'inline'
		),
		'sup' => array(
			'tag' => 'sup',
			'type' => 'inline',
			'allowed' => 'inline'
		)
	);
	
}
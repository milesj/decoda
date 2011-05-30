<?php

class TextFilter extends DecodaFilter {

	protected $_tags = array(  
		'font' => array(
			'tag' => 'span',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(.*?)'
			),
			'format' => 'style="font-family: \'{default}\', sans-serif;"'
		),
		'size' => array(
			'tag' => 'span',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '((?:[1-2]{1})?[0-9]{1})',
			),
			'format' => 'style="font-size: {default}px"'
		),
		'color' => array(
			'tag' => 'span',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(#[0-9a-fA-F]{3,6}|[a-z]+)'
			),
			'format' => 'style="color: {default}"'
		),
		'h1' => array(
			'tag' => 'h1',
			'type' => 'block',
			'allowed' => 'inline'
		),
		'h2' => array(
			'tag' => 'h2',
			'type' => 'block',
			'allowed' => 'inline'
		),
		'h3' => array(
			'tag' => 'h3',
			'type' => 'block',
			'allowed' => 'inline'
		),
		'h4' => array(
			'tag' => 'h4',
			'type' => 'block',
			'allowed' => 'inline'
		),
		'h5' => array(
			'tag' => 'h5',
			'type' => 'block',
			'allowed' => 'inline'
		),
		'h6' => array(
			'tag' => 'h6',
			'type' => 'block',
			'allowed' => 'inline'
		)
	);
	
}
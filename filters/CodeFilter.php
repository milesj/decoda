<?php

class CodeFilter extends DecodaFilter {

	protected $_tags = array(  
		'code' => array(   
			'tag' => 'pre',
			'type' => 'block',
			'allowed' => 'all',
			'attributes' => array(
				'lang' => '([-_\sa-zA-Z0-9]+)',
				'hl' => '([0-9,]+)'
			),
			'convert' => false
		),
		'decode' => array(
			'tag' => 'pre',
			'type' => 'block',
			'allowed' => 'all',
			'attributes' => array(
				'lang' => '([-_\sa-zA-Z0-9]+)',
				'hl' => '([0-9,]+)'
			),
			'convert' => false
		),
		'var' => array(
			'tag' => 'var',
			'type' => 'inline',
			'allowed' => 'inline'
		)
	);
	
}
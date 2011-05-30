<?php

class EmailFilter extends DecodaFilter {

	protected $_tags = array(  
		'email' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(.*?)'
			)
		),
		'mail' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(.*?)'
			)
		)
	);
	
}
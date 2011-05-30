<?php

class QuoteFilter extends DecodaFilter {

	protected $_tags = array(  
		'quote' => array(
			'tag' => array('quote'),
			'type' => 'block',
			'allowed' => 'all',
			'attributes' => array(
				'default' => '(.*?)',
				'date' => '(.*?)'
			)
		)
	);
	
}
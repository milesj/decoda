<?php

class ListFilter extends DecodaFilter {

	protected $_tags = array(  
		'olist' => array(
			'tag' => 'ol',
			'type' => 'block',
			'allowed' => array('li')
		),
		'list' => array(
			'tag' => 'ul',
			'type' => 'block',
			'allowed' => array('li')
		),
		'li' => array(
			'tag' => 'li',
			'type' => 'block',
			'allowed' => 'all',
			'parent' => array('olist', 'list')
		)
	);
	
}
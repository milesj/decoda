<?php

class ImageFilter extends DecodaFilter {

	protected $_tags = array(  
		'img' => array(
			'tag' => 'img',
			'type' => 'inline',
			'allowed' => 'none',
			'attributes' => array(
				'width' => '([0-9%]{1,4}+)',
				'height' => '([0-9%]{1,4}+)'
			)
		),
		'image' => array(
			'tag' => 'img',
			'type' => 'inline',
			'allowed' => 'none',
			'attributes' => array(
				'width' => '([0-9%]{1,4}+)',
				'height' => '([0-9%]{1,4}+)'
			)
		)
	);
	
}
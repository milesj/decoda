<?php

class VideoFilter extends DecodaFilter {

	protected $_tags = array(  
		'video' => array(
			'tag' => array('quote'),
			'type' => 'block',
			'allowed' => 'none',
			'attributes' => array(
				'default' => '(.*?)',
				'size' => '(.*?)'
			)
		)
	);
	
}
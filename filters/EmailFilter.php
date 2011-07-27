<?php

class EmailFilter extends DecodaFilter {

	protected $_tags = array(  
		'email' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'mail' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '(.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		)
	);
	
	public function parse($tag, $content) {
		$email = $tag['attributes']['default'];
		$length = strlen($email);
		$encrypted = '';

		if ($length > 0) {
			for ($i = 0; $i < $length; ++$i) {
				$encrypted .= '&#' . ord(substr($email, $i, 1)) . ';';
			}
		}

		$tag['attributes']['default'] = 'mailto:'. $encrypted;
		
		return parent::parse($tag, $content);
	}
	
}
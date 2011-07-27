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
	
	public function parse($tag, $content) {
		$attributes = $tag['attributes'];
		
		if (isset($attributes['default'])) {
			$email = $attributes['default'];
			unset($attributes['default']);
			
			$length = strlen($email);
			$encrypted = '';

			if ($length > 0) {
				for ($i = 0; $i < $length; ++$i) {
					$encrypted .= '&#' . ord(substr($email, $i, 1)) . ';';
				}
			}
			
			$attributes['href'] = 'mailto:'. $encrypted;
		} else {
			return $parsed;
		}
		
		$parsed  = $this->openTag($tag['tag'], $attributes);
		$parsed .= $content;
		$parsed .= $this->closeTag($tag['tag']);

		return $parsed;
	}
	
}
<?php

class EmailFilter extends DecodaFilter {

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'email' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '(.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'mail' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '(.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		)
	);
	
	/**
	 * Encrypt the email before parsing it within tags.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		$email = $tag['attributes']['default'];
		$length = strlen($email);
		$encrypted = '';

		if ($length > 0) {
			for ($i = 0; $i < $length; ++$i) {
				$encrypted .= '&#' . ord(substr($email, $i, 1)) . ';';
			}
		}

		$tag['attributes']['default'] = 'mailto:'. $encrypted;
		
		if ($this->_parser->config('shorthand')) {
			return '['. parent::parse($tag, Decoda::message('mail')) .']';
		}
		
		return parent::parse($tag, $content);
	}
	
}
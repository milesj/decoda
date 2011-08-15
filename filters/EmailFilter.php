<?php

class EmailFilter extends DecodaFilter {

	/**
	 * Regex pattern.
	 */
	const EMAIL_PATTERN = '/(^|\n|\s)([-a-zA-Z0-9\.\+!]{1,64}+)@([-a-zA-Z0-9\.]{5,255}+)/';

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
			'pattern' => self::EMAIL_PATTERN,
			'attributes' => array(
				'default' => self::EMAIL_PATTERN
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'mail' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'pattern' => self::EMAIL_PATTERN,
			'attributes' => array(
				'default' => self::EMAIL_PATTERN
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
		$email = $content;
		$default = false;
		$length = strlen($email);
		$encrypted = '';

		if (isset($tag['attributes']['default'])) {
			$email = $tag['attributes']['default'];
			$default = true;
		}

		if ($length > 0) {
			for ($i = 0; $i < $length; ++$i) {
				$encrypted .= '&#' . ord(substr($email, $i, 1)) . ';';
			}
		}

		$tag['attributes']['default'] = 'mailto:'. $encrypted;

		if ($this->getParser()->config('shorthand')) {
			return '['. parent::parse($tag, $this->message('mail')) .']';
		}

		if (!$default) {
			$content = $encrypted;
		}

		return parent::parse($tag, $content);
	}

}
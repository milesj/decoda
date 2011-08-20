<?php

class UrlFilter extends DecodaFilter {

	/**
	 * Regex pattern.
	 */
	const URL_PATTERN = '/^((?:http|ftp|irc|file|telnet)s?:\/\/)(.*?)$/is';

	/**
	 * Supported tags.
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(  
		'url' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'pattern' => self::URL_PATTERN,
			'attributes' => array(
				'default' => self::URL_PATTERN
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'link' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'pattern' => self::URL_PATTERN,
			'attributes' => array(
				'default' => self::URL_PATTERN
			),
			'map' => array(
				'default' => 'href'
			)
		)
	);

	/**
	 * Using shorthand variation if enabled.
	 * 
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		if (empty($tag['attributes']['default'])) {
			$tag['attributes']['default'] = $content;
		}
		
		if ($this->getParser()->config('shorthand')) {
			$tag['content'] = $this->message('link');
			
			return '['. parent::parse($tag, $content) .']';
		}

		return parent::parse($tag, $content);
	}

}
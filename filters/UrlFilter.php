<?php

class UrlFilter extends DecodaFilter {

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
			'attributes' => array(
				'default' => '((?:http|ftp|irc|file|telnet)s?:\/\/.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'link' => array(
			'tag' => 'a',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_INLINE,
			'attributes' => array(
				'default' => '((?:http|ftp|irc|file|telnet)s?:\/\/.*?)'
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
		if ($this->_parser->config('shorthand')) {
			return '['. parent::parse($tag, Decoda::message('link')) .']';
		}
		
		return parent::parse($tag, $content);
	}
	
}
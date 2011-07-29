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
	
}
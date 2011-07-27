<?php

class UrlFilter extends DecodaFilter {

	protected $_tags = array(  
		'url' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '((?:http|ftp|irc|file|telnet)s?:\/\/.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		),
		'link' => array(
			'tag' => 'a',
			'type' => 'inline',
			'allowed' => 'inline',
			'attributes' => array(
				'default' => '((?:http|ftp|irc|file|telnet)s?:\/\/.*?)'
			),
			'map' => array(
				'default' => 'href'
			)
		)
	);
	
}
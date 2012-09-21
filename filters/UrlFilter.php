<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\FilterAbstract;

/**
 * Provides tags for URLs.
 *
 * @package	mjohnson.decoda.filters
 */
class UrlFilter extends FilterAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'protocols' => array('http', 'ftp', 'irc', 'telnet')
	);

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'url' => array(
			'htmlTag' => 'a',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE,
			'attributes' => array(
				'default' => true
			),
			'mapAttributes' => array(
				'default' => 'href'
			)
		),
		'link' => array(
			'htmlTag' => 'a',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_INLINE,
			'attributes' => array(
				'default' => true
			),
			'mapAttributes' => array(
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
		$url = isset($tag['attributes']['href']) ? $tag['attributes']['href'] : $content;
		$protocols = $this->config('protocols');

		// Return an invalid URL
		if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/^(' . implode('|', $protocols) . ')/i', $url)) {
			return $url;
		}

		$tag['attributes']['href'] = $url;

		if ($this->getParser()->config('shorthand')) {
			$tag['content'] = $this->message('link');

			return '[' . parent::parse($tag, $content) . ']';
		}

		return parent::parse($tag, $content);
	}

}
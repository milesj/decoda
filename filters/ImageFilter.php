<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\filters\FilterAbstract;

/**
 * Provides tags for images.
 *
 * @package	mjohnson.decoda.filters
 */
class ImageFilter extends FilterAbstract {

	/**
	 * Regex pattern.
	 */
	const IMAGE_PATTERN = '/^(?:https?:\/\/)?(.*?)\.(jpg|jpeg|png|gif|bmp)$/is';

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'img' => array(
			'tag' => 'img',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_NONE,
			'pattern' => self::IMAGE_PATTERN,
			'autoClose' => true,
			'attributes' => array(
				'width' => '/[0-9%]{1,4}+/',
				'height' => '/[0-9%]{1,4}+/',
				'alt' => '/.*?/'
			)
		),
		'image' => array(
			'tag' => 'img',
			'type' => self::TYPE_INLINE,
			'allowed' => self::TYPE_NONE,
			'pattern' => self::IMAGE_PATTERN,
			'autoClose' => true,
			'attributes' => array(
				'width' => '/[0-9%]{1,4}+/',
				'height' => '/[0-9%]{1,4}+/',
				'alt' => '/.*?/'
			)
		)
	);

	/**
	 * Use the content as the image source.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		// If more than 1 http:// is found in the string, possible XSS attack
		if (substr_count($content, 'http://') > 1) {
			return null;
		}

		$tag['attributes']['src'] = $content;

		if (empty($tag['attributes']['alt'])) {
			$tag['attributes']['alt'] = '';
		}

		return parent::parse($tag, $content);
	}

}
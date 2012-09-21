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
 * Provides tags for images.
 *
 * @package	mjohnson.decoda.filters
 */
class ImageFilter extends FilterAbstract {

	/**
	 * Regex pattern.
	 */
	const IMAGE_PATTERN = '/^(?:https?:)?\/\/(.*?)\.(?:jpg|jpeg|png|gif|bmp)$/is';
	const DIMENSION = '/^[0-9%]{1,4}+$/';

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'img' => array(
			'htmlTag' => 'img',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::IMAGE_PATTERN,
			'autoClose' => true,
			'attributes' => array(
				'width' => self::DIMENSION,
				'height' => self::DIMENSION,
				'alt' => self::WILDCARD
			)
		),
		'image' => array(
			'htmlTag' => 'img',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::IMAGE_PATTERN,
			'autoClose' => true,
			'attributes' => array(
				'width' => self::DIMENSION,
				'height' => self::DIMENSION,
				'alt' => self::WILDCARD
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
		if ((substr_count($content, 'http://') + substr_count($content, 'https://')) > 1) {
			return null;
		}

		$tag['attributes']['src'] = $content;

		if (empty($tag['attributes']['alt'])) {
			$tag['attributes']['alt'] = '';
		}

		return parent::parse($tag, $content);
	}

}
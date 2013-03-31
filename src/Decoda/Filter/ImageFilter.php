<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for images.
 */
class ImageFilter extends AbstractFilter {

	/**
	 * Regex pattern.
	 */
	const IMAGE_PATTERN = '/^(?:https?:\/)?(?:.){0,2}\/(.*?)\.(?:jpg|jpeg|png|gif|bmp)$/is';
	const WIDTH_HEIGHT = '/^([0-9%]{1,4}+)x([0-9%]{1,4}+)$/';
	const DIMENSION = '/^[0-9%]{1,4}+$/';

	/**
	 * Supported tags.
	 *
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
				'default' => self::WIDTH_HEIGHT,
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
				'default' => self::WIDTH_HEIGHT,
				'width' => self::DIMENSION,
				'height' => self::DIMENSION,
				'alt' => self::WILDCARD
			)
		)
	);

	/**
	 * Use the content as the image source.
	 *
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {

		// If more than 1 http:// is found in the string, possible XSS attack
		if ((mb_substr_count($content, 'http://') + mb_substr_count($content, 'https://')) > 1) {
			return null;
		}

		$tag['attributes']['src'] = $content;

		if (!empty($tag['attributes']['default'])) {
			list($width, $height) = explode('x', $tag['attributes']['default']);

			$tag['attributes']['width'] = $width;
			$tag['attributes']['height'] = $height;
		}

		if (empty($tag['attributes']['alt'])) {
			$tag['attributes']['alt'] = '';
		}

		return parent::parse($tag, $content);
	}

}
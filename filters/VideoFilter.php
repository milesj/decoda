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
 * Provides the tag for videos. Only a few video services are supported.
 *
 * @package	mjohnson.decoda.filters
 */
class VideoFilter extends FilterAbstract {

	/**
	 * Regex pattern.
	 */
	const VIDEO_PATTERN = '/^[-_a-z0-9]+$/is';

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'video' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'default' => self::ALPHA,
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'youtube' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'vimeo' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'veoh' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'liveleak' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'dailymotion' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'myspace' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'wegame' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		),
		'collegehumor' => array(
			'template' => 'video',
			'displayType' => Decoda::TYPE_BLOCK,
			'allowedTypes' => Decoda::TYPE_NONE,
			'contentPattern' => self::VIDEO_PATTERN,
			'attributes' => array(
				'size' => '/^(?:small|medium|large)$/i'
			)
		)
	);

	/**
	 * Video formats.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_formats = array(
		'youtube' => array(
			'small' => array(560, 315),
			'medium' => array(640, 360),
			'large' => array(853, 480),
			'player' => 'iframe',
			'path' => '//youtube.com/embed/{id}'
		),
		'vimeo' => array(
			'small' => array(400, 225),
			'medium' => array(550, 309),
			'large' => array(700, 394),
			'player' => 'iframe',
			'path' => '//player.vimeo.com/video/{id}'
		),
		'liveleak' => array(
			'small' => array(560, 315),
			'medium' => array(640, 360),
			'large' => array(853, 480),
			'player' => 'iframe',
			'path' => '//liveleak.com/e/{id}'
		),
		'veoh' => array(
			'small' => array(410, 341),
			'medium' => array(610, 507),
			'large' => array(810, 674),
			'player' => 'embed',
			'path' => '//veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId={id}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous'
		),
		'dailymotion' => array(
			'small' => array(320, 180),
			'medium' => array(480, 270),
			'large' => array(560, 315),
			'player' => 'iframe',
			'path' => '//dailymotion.com/embed/video/{id}'
		),
		'myspace' => array(
			'small' => array(325, 260),
			'medium' => array(425, 340),
			'large' => array(525, 420),
			'player' => 'embed',
			'path' => '//mediaservices.myspace.com/services/media/embed.aspx/m={id},t=1,mt=video'
		),
		'wegame' => array(
			'small' => array(325, 223),
			'medium' => array(480, 330),
			'large' => array(640, 440),
			'player' => 'embed',
			'path' => '//wegame.com/static/flash/player.swf?xmlrequest=http://www.wegame.com/player/video/{id}&embedPlayer=true'
		),
		'collegehumor' => array(
			'small' => array(300, 169),
			'medium' => array(450, 254),
			'large' => array(600, 338),
			'player' => 'iframe',
			'path' => '//collegehumor.com/e/{id}'
		)
	);

	/**
	 * Custom build the HTML for videos.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		$provider = isset($tag['attributes']['default']) ? $tag['attributes']['default'] : $tag['tag'];
		$size = strtolower(isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium');

		if (empty($this->_formats[$provider])) {
			return sprintf('(Invalid %s video code)', $provider);
		}

		$video = $this->_formats[$provider];
		$size = isset($video[$size]) ? $video[$size] : $video['medium'];

		$tag['attributes']['width'] = $size[0];
		$tag['attributes']['height'] = $size[1];
		$tag['attributes']['player'] = $video['player'];
		$tag['attributes']['url'] = str_replace(array('{id}', '{width}', '{height}'), array($content, $size[0], $size[1]), $video['path']);

		return parent::parse($tag, $content);
	}

}
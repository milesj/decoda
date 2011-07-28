<?php

class VideoFilter extends DecodaFilter {

	protected $_tags = array(  
		'video' => array(
			'type' => 'block',
			'allowed' => 'none',
			'attributes' => array(
				'default' => '([a-zA-Z0-9]+)',
				'size' => '(small|medium|large)'
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
			'small' => array(560, 349),
			'medium' => array(640, 390),
			'large' => array(853, 515),
			'player' => 'embed',
			'path' => 'http://youtube.com/v/{id}'
		),
		'vimeo' => array(
			'small' => array(400, 225),
			'medium' => array(550, 309),
			'large' => array(700, 394),
			'player' => 'iframe',
			'path' => 'http://player.vimeo.com/video/{id}'
		),
		'liveleak' => array(
			'small' => array(450, 370),
			'medium' => array(600, 493),
			'large' => array(750, 617),
			'player' => 'embed',
			'path' => 'http://liveleak.com/e/{id}'
		),
		'veoh' => array(
			'small' => array(410, 341),
			'medium' => array(610, 507),
			'large' => array(810, 674),
			'player' => 'embed',
			'path' => 'http://veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.3.1004&permalinkId={id}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous'
		),
		'dailymotion' => array(
			'small' => array(320, 240),
			'medium' => array(480, 360),
			'large' => array(560, 420),
			'player' => 'embed',
			'path' => 'http://dailymotion.com/swf/video/{id}&additionalInfos=0&autoPlay=0'
		),
		'myspace' => array(
			'small' => array(325, 260),
			'medium' => array(425, 340),
			'large' => array(525, 420),
			'player' => 'embed',
			'path' => 'http://mediaservices.myspace.com/services/media/embed.aspx/m={id},t=1,mt=video'
		)
	);
	
	public function parse($tag, $content) {
		$provider = $tag['attributes']['default'];
		$size = isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium';
		
		if (empty($this->_formats[$provider])) {
			return $provider .':'. $content;
		}

		$video = $this->_formats[$provider];
		$path = str_replace('{id}', $content, $video['path']);
		$size = isset($video[$size]) ? $video[$size] : $video['medium'];

		if ($video['player'] == 'embed') {
			return '<embed src="'. $path .'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'. $size[0] .'" height="'. $size[1] .'"></embed>';
		} else {
			return '<iframe src="'. $path .'" width="'. $size[0] .'" height="'. $size[1] .'" frameborder="0"></iframe>';
		}
	}
	
}
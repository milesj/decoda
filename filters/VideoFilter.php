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
	
	public function parse($tag, $content) {
		$supported = DecodaConfig::videos();
		$provider = $tag['attributes']['default'];
		$size = isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium';
		
		if (empty($supported[$provider])) {
			return $provider .':'. $content;
		}

		$video = $supported[$provider];
		$path = str_replace('{id}', $content, $video['path']);
		$size = isset($video[$size]) ? $video[$size] : $video['medium'];

		if ($video['player'] == 'embed') {
			return '<embed src="'. $path .'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'. $size[0] .'" height="'. $size[1] .'"></embed>';
		} else {
			return '<iframe src="'. $path .'" width="'. $size[0] .'" height="'. $size[1] .'" frameborder="0"></iframe>';
		}
	}
	
}
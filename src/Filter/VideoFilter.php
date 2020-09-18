<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides the tag for videos. Only a few video services are supported.
 */
class VideoFilter extends AbstractFilter {

    /**
     * Regex pattern.
     */
    const VIDEO_PATTERN = '/^[-_a-z0-9]+$/is';
    const SIZE_PATTERN = '/^(?:small|medium|large)$/i';

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = [
        'video' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'default' => self::ALPHA,
                'size' => self::SIZE_PATTERN
            ]
        ],
        'youtube' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'vimeo' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'veoh' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'vevo' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                //'size' => self::SIZE_PATTERN Vevo has no sizes
            ]
        ],
        'liveleak' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'dailymotion' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'myspace' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'collegehumor' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'funnyordie' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
        'wegame' => [
            'template' => 'video',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::VIDEO_PATTERN,
            'attributes' => [
                'size' => self::SIZE_PATTERN
            ]
        ],
    ];

    /**
     * Video formats.
     *
     * @type array
     */
    protected $_formats = [
        'youtube' => [
            'small' => [560, 315],
            'medium' => [640, 360],
            'large' => [853, 480],
            'player' => 'iframe',
            'path' => '//youtube.com/embed/{id}?rel=0'
        ],
        'vimeo' => [
            'small' => [400, 225],
            'medium' => [550, 309],
            'large' => [700, 394],
            'player' => 'iframe',
            'path' => '//player.vimeo.com/video/{id}'
        ],
        'vevo' => [
            'small' => [400, 225],
            'medium' => [575, 324],
            'large' => [955, 538],
            'player' => 'embed',
            'path' => '//videoplayer.vevo.com/embed/Embedded?videoId={id}&playlist=false&autoplay=0&playerId=62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961&playerType=embedded'
        ],
        'veoh' => [
            'small' => [410, 341],
            'medium' => [610, 507],
            'large' => [810, 674],
            'player' => 'embed',
            'path' => '//veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&permalinkId={id}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous'
        ],
        'liveleak' => [
            'small' => [560, 315],
            'medium' => [640, 360],
            'large' => [853, 480],
            'player' => 'iframe',
            'path' => '//liveleak.com/e/{id}'
        ],
        'dailymotion' => [
            'small' => [320, 180],
            'medium' => [480, 270],
            'large' => [560, 315],
            'player' => 'iframe',
            'path' => '//dailymotion.com/embed/video/{id}'
        ],
        'myspace' => [
            'small' => [325, 260],
            'medium' => [425, 340],
            'large' => [525, 420],
            'player' => 'embed',
            'path' => '//mediaservices.myspace.com/services/media/embed.aspx/m={id},t=1,mt=video'
        ],
        'collegehumor' => [
            'small' => [300, 169],
            'medium' => [450, 254],
            'large' => [600, 338],
            'player' => 'iframe',
            'path' => '//collegehumor.com/e/{id}'
        ],
        'funnyordie' => [
            'small' => [512, 328],
            'medium' => [640, 400],
            'large' => [960, 580],
            'player' => 'iframe',
            'path' => '//funnyordie.com/embed/{id}'
        ],
        'wegame' => [
            'small' => [325, 223],
            'medium' => [480, 330],
            'large' => [640, 440],
            'player' => 'embed',
            'path' => '//wegame.com/static/flash/player.swf?xmlrequest=http://www.wegame.com/player/video/{id}&embedPlayer=true'
        ],
    ];

    /**
     * Custom build the HTML for videos.
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function parse(array $tag, $content) {
        $provider = isset($tag['attributes']['default']) ? $tag['attributes']['default'] : $tag['tag'];
        $size = mb_strtolower(isset($tag['attributes']['size']) ? $tag['attributes']['size'] : 'medium');

        if (empty($this->_formats[$provider])) {
            return sprintf('(Invalid %s video code)', $provider);
        }

        $video = $this->_formats[$provider];
        $size = isset($video[$size]) ? $video[$size] : $video['medium'];

        $tag['attributes']['width'] = $size[0];
        $tag['attributes']['height'] = $size[1];
        $tag['attributes']['player'] = $video['player'];
        $tag['attributes']['url'] = str_replace(['{id}', '{width}', '{height}'], [$content, $size[0], $size[1]], $video['path']);

        return parent::parse($tag, $content);
    }

}

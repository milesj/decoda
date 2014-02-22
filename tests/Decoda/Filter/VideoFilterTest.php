<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\VideoFilter;
use Decoda\Test\TestCase;

class VideoFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new VideoFilter());
    }

    /**
     * Test that [video] renders embedded video players.
     */
    public function testVideo() {
        // iframe
        $this->assertEquals('<iframe src="//youtube.com/embed/c0dE" width="640" height="360" frameborder="0"></iframe>', $this->object->reset('[video="youtube"]c0dE[/video]')->parse());
        $this->assertEquals('<iframe src="//youtube.com/embed/c0dE" width="853" height="480" frameborder="0"></iframe>', $this->object->reset('[video="youtube" size="large"]c0dE[/video]')->parse());

        // embed
        $this->assertEquals('<iframe src="//liveleak.com/e/c0dE" width="640" height="360" frameborder="0"></iframe>', $this->object->reset('[video="liveleak"]c0dE[/video]')->parse());
        $this->assertEquals('<iframe src="//liveleak.com/e/c0dE" width="853" height="480" frameborder="0"></iframe>', $this->object->reset('[video="liveleak" size="large"]c0dE[/video]')->parse());

        // invalid
        $this->assertEquals('(Invalid video)', $this->object->reset('[video="youtube"]fake..w123c0code[/video]')->parse());
        $this->assertEquals('(Invalid someVideoService video code)', $this->object->reset('[video="someVideoService"]c0dE[/video]')->parse());
    }

    /**
     * Test that vendor specific video tags work.
     */
    public function testVideoSpecificTags() {
        $this->assertEquals('<iframe src="//youtube.com/embed/c0dE" width="640" height="360" frameborder="0"></iframe>', $this->object->reset('[youtube]c0dE[/youtube]')->parse());
        $this->assertEquals('<iframe src="//player.vimeo.com/video/c0dE" width="700" height="394" frameborder="0"></iframe>', $this->object->reset('[vimeo size="large"]c0dE[/vimeo]')->parse());
        $this->assertEquals('<iframe src="//liveleak.com/e/c0dE" width="560" height="315" frameborder="0"></iframe>', $this->object->reset('[liveleak size="small"]c0dE[/liveleak]')->parse());
        $this->assertEquals('<embed src="//veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1390&amp;permalinkId=c0dE&amp;player=videodetailsembedded&amp;videoAutoPlay=0&amp;id=anonymous" width="610" height="507" type="application/x-shockwave-flash"></embed>', $this->object->reset('[veoh size="medium"]c0dE[/veoh]')->parse());
        $this->assertEquals('<iframe src="//dailymotion.com/embed/video/c0dE" width="480" height="270" frameborder="0"></iframe>', $this->object->reset('[dailymotion]c0dE[/dailymotion]')->parse());
        $this->assertEquals('<embed src="//mediaservices.myspace.com/services/media/embed.aspx/m=c0dE,t=1,mt=video" width="525" height="420" type="application/x-shockwave-flash"></embed>', $this->object->reset('[myspace size="large"]c0dE[/myspace]')->parse());
        $this->assertEquals('<embed src="//wegame.com/static/flash/player.swf?xmlrequest=http://www.wegame.com/player/video/c0dE&amp;embedPlayer=true" width="480" height="330" type="application/x-shockwave-flash"></embed>', $this->object->reset('[wegame size="medium"]c0dE[/wegame]')->parse());
        $this->assertEquals('<iframe src="//collegehumor.com/e/c0dE" width="300" height="169" frameborder="0"></iframe>', $this->object->reset('[collegehumor size="small"]c0dE[/collegehumor]')->parse());
    }

}
<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\filters;

use mjohnson\decoda\filters\VideoFilter;
use mjohnson\decoda\tests\TestCase;

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
		$this->assertEquals('<iframe src="http://www.youtube.com/embed/c0dE" width="640" height="360" frameborder="0"></iframe>', $this->object->reset('[video="youtube"]c0dE[/video]')->parse());
		$this->assertEquals('<iframe src="http://www.youtube.com/embed/c0dE" width="853" height="480" frameborder="0"></iframe>', $this->object->reset('[video="youtube" size="large"]c0dE[/video]')->parse());

		// embed
		$this->assertEquals('<embed src="http://liveleak.com/e/c0dE" width="600" height="493" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>', $this->object->reset('[video="liveleak"]c0dE[/video]')->parse());
		$this->assertEquals('<embed src="http://liveleak.com/e/c0dE" width="750" height="617" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>', $this->object->reset('[video="liveleak" size="large"]c0dE[/video]')->parse());

		// invalid
		$this->assertEquals('(Invalid video)', $this->object->reset('[video="youtube"]fake..w123c0code[/video]')->parse());
		$this->assertEquals('(Invalid someVideoService video code)', $this->object->reset('[video="someVideoService"]c0dE[/video]')->parse());
	}

}
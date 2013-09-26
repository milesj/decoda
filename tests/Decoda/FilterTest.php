<?php
/**
 * @copyright   2006-2013, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Test\TestCase;
use Decoda\Test\TestFilter;

class FilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new TestFilter();
    }

    /**
     * Test that parse() renders a Decoda tag into an HTML tag.
     */
    public function testParse() {
        $this->object->setParser(new Decoda());

        $this->assertEquals('<example>Content</example>', $this->object->parse(array(
            'tag' => 'example',
            'text' => '[example]',
            'attributes' => array(),
            'type' => Decoda::TAG_OPEN,
            'children' => array('Content')
        ), 'Content'));
    }

    /**
     * Test that getTags() returns all tags.
     */
    public function testGetTags() {
        $this->assertEquals(array(
            'example',
            'template',
            'templateMissing',
            'inline',
            'inlineAllowInline',
            'inlineAllowBlock',
            'inlineAllowBoth',
            'block',
            'blockAllowInline',
            'blockAllowBlock',
            'blockAllowBoth',
            'attributes',
            'parent',
            'parentNoPersist',
            'parentWhitelist',
            'parentBlacklist',
            'whiteChild',
            'blackChild',
            'depth',
            'lineBreaksRemove',
            'lineBreaksPreserve',
            'lineBreaksConvert',
            'pattern',
            'autoClose'
        ), array_keys($this->object->getTags()));
    }

    /**
     * Test that getTag() returns a tag settings and the defaults.
     */
    public function testGetTag() {
        $expected = array(
            'tag' => 'fakeTag',
            'htmlTag' => '',
            'template' => '',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'attributes' => array(),
            'mapAttributes' => array(),
            'htmlAttributes' => array(),
            'escapeAttributes' => true,
            'lineBreaks' => Decoda::NL_CONVERT,
            'autoClose' => false,
            'preserveTags' => false,
            'onlyTags' => false,
            'contentPattern' => '',
            'stripContent' => false,
            'parent' => array(),
            'childrenWhitelist' => array(),
            'childrenBlacklist' => array(),
            'maxChildDepth' => -1,
            'persistContent' => true
        );

        $this->assertEquals($expected, $this->object->getTag('fakeTag'));

        $expected['tag'] = 'example';
        $expected['htmlTag'] = 'example';

        $this->assertEquals($expected, $this->object->getTag('example'));
    }

}


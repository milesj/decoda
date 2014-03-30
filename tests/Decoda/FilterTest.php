<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
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
            'autoClose',
            'aliasBase',
            'aliased'
        ), array_keys($this->object->getTags()));
    }

    /**
     * Test that getTag() returns a tag settings and the defaults.
     */
    public function testGetTag() {
        $expected = array(
            'tag' => 'example',
            'htmlTag' => 'example',
            'template' => '',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'aliasFor' => '',
            'attributes' => array(),
            'mapAttributes' => array(),
            'htmlAttributes' => array(),
            'aliasAttributes' => array(),
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

        $this->assertEquals($expected, $this->object->getTag('example'));

        try {
            $this->object->getTag('fakeTag');
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
     * Test that aliasing works.
     */
    public function testGetTagAliasing() {
        $this->assertEquals(array(
            'tag' => 'aliasBase',
            'htmlTag' => 'aliasBase',
            'template' => '',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'aliasFor' => '',
            'attributes' => array(
                'foo' => Filter::WILDCARD,
                'bar' => Filter::WILDCARD
            ),
            'mapAttributes' => array(),
            'htmlAttributes' => array(),
            'aliasAttributes' => array(),
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
        ), $this->object->getTag('aliasBase'));

        $this->assertEquals(array(
            'tag' => 'aliased',
            'htmlTag' => 'aliasBase',
            'template' => '',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BOTH,
            'aliasFor' => 'aliasBase',
            'attributes' => array(
                'baz' => Filter::NUMERIC, // NEW
                'foo' => Filter::WILDCARD,
                'bar' => Filter::WILDCARD
            ),
            'mapAttributes' => array(),
            'htmlAttributes' => array(),
            'aliasAttributes' => array(),
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
        ), $this->object->getTag('aliased'));
    }

}


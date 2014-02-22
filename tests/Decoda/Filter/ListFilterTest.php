<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Filter\ListFilter;
use Decoda\Test\TestCase;

class ListFilterTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object->addFilter(new ListFilter());
    }

    /**
     * Test that [list] renders ul lists and only accepts li children.
     */
    public function testList() {
        $this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset('[list][/list]')->parse());

        // children
        $this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset("[list]\n\n\nOnly li's are allowed here\n\n\n[/list]")->parse());
        $this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list][li]List item[/li][/list]")->parse());
        $this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list]\n[li]List item[/li]\n\n\n[/list]")->parse());

        // whitelist
        $this->assertEquals('<ul class="decoda-list"></ul>', $this->object->reset("[list][b]Not a list item[/b][/list]")->parse());
    }

    /**
     * Test that [olist] renders ol lists and only accepts li children.
     */
    public function testOlist() {
        $this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset('[olist][/olist]')->parse());

        // children
        $this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset("[olist]\n\n\nOnly li's are allowed here\n\n\n[/olist]")->parse());
        $this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist][li]List item[/li][/olist]")->parse());
        $this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist]\n[li]List item[/li]\n\n\n[/olist]")->parse());

        // whitelist
        $this->assertEquals('<ol class="decoda-olist"></ol>', $this->object->reset("[olist][b]Not a list item[/b][/olist]")->parse());
    }

    /**
     * Test that [li] renders li tags and only within lists.
     */
    public function testLi() {
        $this->assertEquals('<ul class="decoda-list"><li>List item</li></ul>', $this->object->reset("[list][li]List item[/li][/list]")->parse());
        $this->assertEquals('<ol class="decoda-olist"><li>List item</li></ol>', $this->object->reset("[olist][li]List item[/li][/olist]")->parse());

        // must be within list or olist
        $this->assertEquals('List item', $this->object->reset('[li]List item[/li]')->parse());
        $this->assertEquals('[b]List item[/b]', $this->object->reset('[b][li]List item[/li][/b]')->parse());
    }

    /**
     * Test that list types can be defined through the default attribute.
     */
    public function testListDefault() {
        $this->assertEquals('<ul class="decoda-list type-square"></ul>', $this->object->reset('[list="square"][/list]')->parse());
        $this->assertEquals('<ul class="decoda-list type-upper-roman"></ul>', $this->object->reset('[list="upper-roman"][/list]')->parse());
    }

    /**
     * Test star list items [*].
     */
    public function testListStar() {
        $this->assertEquals('<ul class="decoda-list"><li>Item 1</li><li>Item 2</li></ul>', $this->object->reset("[list][*]Item 1[*]Item 2[/list]")->parse());
        $this->assertEquals('<ul class="decoda-list"><li>Item 1</li><li>Item 2</li></ul>', $this->object->reset("[list][*]Item 1[/*][*]Item 2[/list]")->parse());
        $this->assertEquals('<ul class="decoda-list"><li>Item 1</li><li>Item 2</li></ul>', $this->object->reset("[list][*]Item 1[*]Item 2[/*][/list]")->parse());
        $this->assertEquals('<ul class="decoda-list"><li>Item 1</li><li>Item 2</li></ul>', $this->object->reset("[list][*]Item 1[/*][*]Item 2[/*][/list]")->parse());

        // With other tags
        $this->object->addFilter(new DefaultFilter())->addFilter(new TextFilter());

        $this->assertEquals('<ul class="decoda-list"><li>Item <b>1</b></li><li>Item <span style="font-size: 15px">2</span></li></ul>', $this->object->reset("[list][*]Item [b]1[/b][*]Item [size=\"15\"]2[/size][/list]")->parse());

        // Empty values
        $this->assertEquals('<ul class="decoda-list"><li></li><li>Item 2</li></ul>', $this->object->reset("[list][*][*]Item 2[/list]")->parse());
        $this->assertEquals('Item 1', $this->object->reset("[*]Item 1")->parse());
    }

    /**
     * Stars do not allow nesting.
     */
    public function testNestedStars() {
        $this->assertEquals('<ul class="decoda-list"><li>Item 1</li><li>Item 1</li><li>Item 2</li><li>Item 2</li></ul>', $this->object->reset("[list][*]Item 1[olist][*]Item 1[*]Item 2[/olist][*]Item 2[/list]")->parse());
    }

}
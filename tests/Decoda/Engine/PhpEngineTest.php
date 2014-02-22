<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Decoda;
use Decoda\Engine\PhpEngine;
use Decoda\Test\TestCase;
use Decoda\Test\TestFilter;
use \Exception;

class PhpEngineTest extends TestCase {

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new PhpEngine();
        $this->object->addPath(TEST_DIR . '/templates/');
        $this->object->setFilter(new TestFilter());
    }

    /**
     * Test that render() renders a template and extracts attribute variables.
     */
    public function testRender() {
        $this->assertEquals('foobar', $this->object->render(array(
            'tag' => 'template',
            'attributes' => array('var' => 'foobar')
        ), null));

        $this->assertEquals('', $this->object->render(array(
            'tag' => 'template',
            'attributes' => array('var' => '')
        ), null));

        try {
            $this->object->render(array(
                'tag' => 'templateMissing',
                'attributes' => array('var' => '')
            ), null);

            $this->assertTrue(false);

        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }

}
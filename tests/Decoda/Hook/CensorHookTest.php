<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Hook\CensorHook;
use Decoda\Test\TestCase;

class CensorHookTest extends TestCase {

    protected $equivalentChars = '@#$*!?%';

    /**
     * Set up Decoda.
     */
    protected function setUp() {
        parent::setUp();

        $this->object = new CensorHook();
        $this->object->setParser(new Decoda());
        $this->object->startup();
    }

    /**
     * Test that beforeParse() will convert curse words to a censored equivalent. Will also take into account mulitple characters.
     */
    public function testParse() {
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(4)), $this->object->beforeParse('fuck'));
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(12)), $this->object->beforeParse('fuuuccckkkkk'));
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(14)), $this->object->beforeParse('fffUUUcccKKKkk'));
        $this->assertRegExp(sprintf('/%s %s %s/', $this->getCensoredRegex(4), $this->getCensoredRegex(5), $this->getCensoredRegex(6)), $this->object->beforeParse('fuck fuckk fucckk'));
        $this->assertRegExp(sprintf('/Hey, %s you buddy!/', $this->getCensoredRegex(4)), $this->object->beforeParse('Hey, fuck you buddy!'));

        // Don't censor words that share a blacklist
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(3)), $this->object->beforeParse('nig'));
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(6)), $this->object->beforeParse('nigger'));
        $this->assertEquals('Night', $this->object->beforeParse('Night'));
    }

    /**
     * Test that blacklist() censors words on the fly.
     */
    public function testBlacklist() {
        $this->assertEquals('word', $this->object->beforeParse('word'));

        $this->object->blacklist(array('word'));
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(4)), $this->object->beforeParse('word'));
        $this->assertRegExp(sprintf('/%s/', $this->getCensoredRegex(9)), $this->object->beforeParse('wooRrrDdd'));
    }

    /**
     * Gets the censored equivalent regex with the specified length
     *
     * @param integer $length The number of characters for the equivalent regex
     * @return string The censored equivalent regex with a maximum of 10 letters
     */
    protected function getCensoredRegex($length)
    {
        // The string censored equivalent is limited to 10 letters
        if (10 < $length) {
            $length = 10;
        }

        $regex = sprintf('[%s]{%d}', preg_quote($this->equivalentChars, '/'), $length);

        return $regex;
    }

}
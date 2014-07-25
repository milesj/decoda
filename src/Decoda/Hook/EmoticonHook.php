<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Loader\FileLoader;

/**
 * Converts smiley faces into emoticon images.
 */
class EmoticonHook extends AbstractHook {

    /**
     * Configuration.
     *
     * @type array
     */
    protected $_config = array(
        'path' => '/images/',
        'extension' => 'png'
    );

    /**
     * Mapping of emoticons to smilies.
     *
     * @type array
     */
    protected $_emoticons = array();

    /**
     * Map of smilies to emoticons.
     *
     * @type array
     */
    protected $_smilies = array();

    /**
     * Computes differents catches parts into an array.
     *
     * Generate:
     *     array(
     *         'left'   => 'catches string',  // The catch string starts with it
     *         'repeat' => array(smiley, right, ...), // A repeating sequence
     *     )
     *
     * @type \Closure|NULL
     */
    private $_computeParts;

    /**
     * Read the contents of the loaders into the emoticons list.
     */
    public function startup() {
        if ($this->_emoticons) {
            return;
        }

        // Load files from config paths
        foreach ($this->getParser()->getPaths() as $path) {
            foreach (glob($path . 'emoticons.*') as $file) {
                $this->addLoader(new FileLoader($file));
            }
        }

        // Load the contents into the emoticon and smiley list
        foreach ($this->getLoaders() as $loader) {
            $loader->setParser($this->getParser());

            if ($emoticons = $loader->load()) {
                foreach ($emoticons as $emoticon => $smilies) {
                    foreach ($smilies as $smile) {
                        $this->_smilies[$smile] = $emoticon;
                    }
                }

                $this->_emoticons = array_merge($this->_emoticons, $emoticons);
            }
        }
    }

    /**
     * Parse out the emoticons and replace with images.
     *
     * @param string $content
     * @return string
     */
    public function beforeParse($content) {
        $smilies = $this->getSmilies();

        // Build the smilies regex
        $smiliesRegex = implode('|', array_map(function ($smile) {
            return preg_quote($smile, '/');
        }, $smilies));
        $smiliesRegex = sprintf('(?:%s)', $smiliesRegex);

        // Build the tag regex
        //
        // tag: It is a complete tag. Where `<tag content>` should not contain
        // the start character.
        //     (ex: `[<tag content>]`)
        //
        // openTag: It is an incomplete tag. Where it lacks the end character.
        // Where `<tag content>` should not contain the end character.
        //     (ex: `[<tag content>`)
        //
        // closeTag: It is an incomplete tag. Where it lacks the start character.
        // Where `<tag content>` should not contain the start character.
        //     (ex: `<tag content>]`)
        $openBracket = preg_quote($this->getParser()->getConfig('open'), '/');
        $closeBracket = preg_quote($this->getParser()->getConfig('close'), '/');

        $openTagRegex = sprintf('(?:%s[^%s]+)', $openBracket, $closeBracket);
        $closeTagRegex = sprintf('(?:[^%s]+%s)', $openBracket, $closeBracket);
        $tagRegex = sprintf('(?:%s%s)', $openBracket, $closeTagRegex);

        // Build the regex before the smiley
        //
        // With following delimiters:
        //   * start of string
        //   * space
        //   * newline
        //   * tab
        //   * complete tag
        $leftRegex = sprintf('(?:^|(?!%s)(?:\n|\s)|%s)', $openTagRegex, $tagRegex);

        // Build the regex after the smiley
        //
        // With following delimiters:
        //   * end of string
        //   * space
        //   * newline
        //   * tab
        //   * complete tag
        $rightRegex = sprintf('(?:%s|(?:\n|\s)(?!%s)|$)', $tagRegex, $closeTagRegex);

        // Build the complete regex
        //
        // <left> ( <smiley> <right> ) {1,}
        // The <right> capture is only to keep back compatibility.
        $pattern = sprintf('/(?P<left>%s)(?P<repeat>(?:%s(?<right>%s))+)/is',
            $leftRegex,
            $smiliesRegex,
            $rightRegex
        );

        $this->_computeParts = function ($matches) use ($smiliesRegex, $rightRegex) {
            $pattern = sprintf('/(%s)(%s)/is', $smiliesRegex, $rightRegex);
            $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;

            $repeat = preg_split($pattern, $matches['repeat'], null, $flags);

            return array(
                'left'   => $matches['left'],
                'repeat' => $repeat,
            );
        };

        $content = preg_replace_callback($pattern, array($this, '_emoticonCallbackBC'), $content);

        return $content;
    }

    /**
     * Gets the mapping of emoticons and smilies.
     *
     * @return array
     */
    public function getEmoticons() {
        return $this->_emoticons;
    }

    /**
     * Returns all available smilies.
     *
     * @return array
     */
    public function getSmilies() {
        return array_keys($this->_smilies);
    }

    /**
     * Checks if a smiley is set for the given id.
     *
     * @param string $smiley
     * @return bool
     */
    public function hasSmiley($smiley) {
        return isset($this->_smilies[$smiley]);
    }

    /**
     * Convert a smiley to an HTML representation.
     *
     * @param string $smiley
     * @param bool $isXhtml
     * @return string
     */
    public function render($smiley, $isXhtml = true) {
        if (!$this->hasSmiley($smiley)) {
            return null;
        }

        $path = sprintf('%s%s.%s',
            $this->getConfig('path'),
            $this->_smilies[$smiley],
            $this->getConfig('extension'));

        if ($isXhtml) {
            $tpl = '<img src="%s" alt="" />';
        } else {
            $tpl = '<img src="%s" alt="">';
        }

        return sprintf($tpl, $path);
    }

    /**
     * Callback for smiley processing.
     *
     * This method is just for keep BC.
     * Will be removed on the version 7.
     *
     * @param array $matches
     * @return string
     */
    private function _emoticonCallbackBC($matches) {
        $parts = $this->_computeParts->__invoke($matches);

        $l = $parts['left'];
        $repeatPart = $parts['repeat'];
        $smiley = array_shift($repeatPart);
        $r = array_shift($repeatPart);

        $isXhtmlOutput = $this->getParser()->getConfig('xhtmlOutput');
        foreach ($repeatPart as $key => $part) {
            if (0 === $key % 2) {
                // Part of a smiley.
                // If the smiley regex was validated so it exists.
                $repeatPart[$key] = $this->render($part, $isXhtmlOutput);
            } else {
                // Part after a smiley.
                // no-op
            }
        }

        $r .= implode($repeatPart);

        return $this->_emoticonCallback(array(
            0       => $smiley,
            'left'  => $l,
            1       => $l,
            'right' => $r,
            2       => $r,
        ));
    }

    /**
     * Callback for smiley processing.
     *
     * @param array $matches
     * @return string
     */
    protected function _emoticonCallback($matches) {
        $smiley = trim($matches[0]);

        if (count($matches) === 1 || !$this->hasSmiley($smiley)) {
            return $matches[0];
        }

        $l = isset($matches[1]) ? $matches[1] : '';
        $r = isset($matches[2]) ? $matches[2] : '';

        return $l . $this->render($smiley, $this->getParser()->getConfig('xhtmlOutput')) . $r;
    }

}

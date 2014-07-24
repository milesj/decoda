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

    private $pattern;

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
        $beforeRegex = sprintf('^|(?!%s)(?:\n|\s)|%s', $openTagRegex, $tagRegex);

        // Build the regex after the smiley
        //
        // With following delimiters:
        //   * end of string
        //   * space
        //   * newline
        //   * tab
        //   * complete tag
        $afterRegex = sprintf('%s|(?:\n|\s)(?!%s)|$', $tagRegex, $closeTagRegex);

        // Build the complete regex
        //
        // <left> ( <smiley> <right> ) {1,}
        $pattern = sprintf('/(?P<left>%s)(?P<repeat>(?:(?:%s)(?:%s))+)/is',
            $beforeRegex,
            $smiliesRegex,
            $afterRegex
        );

        $this->pattern = sprintf('/(%s)/is', $afterRegex);

        $content = preg_replace_callback($pattern, array($this, '_emoticonCallback'), $content);

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
     * @param array $matches
     * @return string
     */
    protected function _emoticonCallback($matches) {
        if (count($matches) === 1) {
            return $matches[0];
        }

        $l = isset($matches['left']) ? $matches['left'] : '';
        $r = '';

        $parts = preg_split($this->pattern, $matches['repeat'], null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $isXhtmlOutput = $this->getParser()->getConfig('xhtmlOutput');
        $numberOfParts = count($parts);
        for ($i = 0; $numberOfParts > $i; $i++) {
            $content = $parts[$i];

            if ($this->hasSmiley($content)) {
                $r .= $this->render($content, $isXhtmlOutput);
            } else {
                $r .= $content;
            }
        }

        return $l . $r;
    }

}

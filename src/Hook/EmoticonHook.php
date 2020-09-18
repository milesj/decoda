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
     * @var array
     */
    protected $_config = [
        'path' => '/images/',
        'extension' => 'png'
    ];

    /**
     * Mapping of emoticons to smileys.
     *
     * @var array
     */
    protected $_emoticons = [];

    /**
     * Map of smileys to emoticons.
     *
     * @var string[]
     */
    protected $_smilies = [];

    /**
     * Read the contents of the loaders into the emoticons list.
     */
    public function startup() {
        if ($this->_emoticons) {
            return;
        }

        // Load files from config paths
        foreach ($this->getParser()->getPaths() as $path) {
            $files = glob($path . 'emoticons.*') ?: [];
            foreach ($files as $file) {
                $this->addLoader(new FileLoader($file));
            }
        }

        // Load the contents into the emoticon and smiley list
        foreach ($this->getLoaders() as $loader) {
            $loader->setParser($this->getParser());

            if ($emoticons = $loader->load()) {
                foreach ($emoticons as $emoticon => $smileys) {
                    foreach ($smileys as $smiley) {
                        $this->_smilies[$smiley] = $emoticon;
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
    public function afterParse($content) {
        $smileys = $this->getSmileys();

        // Build the smileys regex
        $smileysRegex = implode('|', array_map(function ($smiley) {
            return preg_quote($smiley, '/');
        }, $smileys));

        $pattern = sprintf('/(?:(?<=[\s.;>:)])|^)(%s)/', $smileysRegex);
        $content = (string)preg_replace_callback($pattern, [$this, '_emoticonCallback'], $content);

        $pattern = sprintf('/(%s)(?:(?=[\s.&<:(])|$)/', $smileysRegex);
        $content = (string)preg_replace_callback($pattern, [$this, '_emoticonCallback'], $content);

        return $content;
    }

    /**
     * Gets the mapping of emoticons and smileys.
     *
     * @return array
     */
    public function getEmoticons() {
        return $this->_emoticons;
    }

    /**
     * Returns all available smileys.
     *
     * @return string[]
     * @deprecated Use getSmileys() instead.
     */
    public function getSmilies() {
        return $this->getSmileys();
    }

    /**
     * Returns all available smileys.
     *
     * @return string[]
     */
    public function getSmileys() {
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
            return '';
        }

        $path = sprintf('%s%s.%s',
            $this->getConfig('path'),
            $this->_smilies[$smiley],
            $this->getConfig('extension'));

        if ($isXhtml) {
            $tpl = '<img class="decoda-emoticon" src="%s" alt="" />';
        } else {
            $tpl = '<img class="decoda-emoticon" src="%s" alt="">';
        }

        return sprintf($tpl, $path);
    }

    /**
     * Callback for smiley processing.
     *
     * @param string[] $matches
     * @return string
     */
    protected function _emoticonCallback($matches) {
        $smiley = trim($matches[0]);

        if (count($matches) === 1 || !$this->hasSmiley($smiley)) {
            return $matches[0];
        }

        return $this->render($smiley, $this->getParser()->getConfig('xhtmlOutput'));
    }

}

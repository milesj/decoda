<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Hook\AbstractHook;

/**
 * Converts smiley faces into emoticon images.
 */
class EmoticonHook extends AbstractHook {

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'path' => '/images/',
		'extension' => 'png'
	);

	/**
	 * Mapping of emoticons and smilies.
	 *
	 * @var array
	 */
	protected $_emoticons = array();

	/**
	 * Map of smilies to emoticons.
	 *
	 * @var array
	 */
	protected $_map = array();

	/**
	 * Parse out the emoticons and replace with images.
	 *
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		foreach ($this->getSmilies() as $smile) {
			$content = preg_replace_callback('/(^|\n|\s)?' . preg_quote($smile, '/') . '(\n|\s|$)?/is', array($this, '_emoticonCallback'), $content);
		}

		return $content;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Hook\EmoticonHook
	 */
	public function setParser(Decoda $parser) {
		parent::setParser($parser);

		$this->_emoticons = array();
		$this->getEmoticons();

		return $this;
	}

	/**
	 * Gets the mapping of emoticons and smilies.
	 *
	 * @return array An array of emoticons
	 */
	protected function getEmoticons() {
		if (!empty($this->_emoticons)) {
			return $this->_emoticons;
		}

		$this->_map = array();
		$this->_emoticons = array();

		if (null === $this->_parser) {
			return $this->_emoticons;
		}

		foreach ($this->_parser->getPaths() as $path) {
			if (!file_exists($path . '/emoticons.json')) {
				continue;
			}

			if ($emoticons = json_decode(file_get_contents($path . '/emoticons.json'), true)) {
				foreach ($emoticons as $emoticon => $smilies) {
					foreach ($smilies as $smile) {
						$this->_map[$smile] = $emoticon;
					}
				}

				$this->_emoticons = array_merge($this->_emoticons, $emoticons);
			}
		}

		return $this->_emoticons;
	}

	/**
	 * Checks if a smiley is set for the given id.
	 *
	 * @param string $smiley  A smiley
	 * @return Boolean true if the smiley is set, false otherwise
	 */
	protected function hasSmiley($smiley) {
		return isset($this->_map[$smiley]);
	}

	/**
	 * Returns all available smilies.
	 *
	 * @return string[] An array of smiley
	 */
	protected function getSmilies() {
		$res = array();

		$emoticons = $this->getEmoticons();

		if ($emoticons) {
			foreach ($emoticons as $smilies) {
				foreach ($smilies as $smiley) {
					$res[] = $smiley;
				}
			}
		}

		return $res;
	}

	/**
	 * Convert a smiley to html representation
	 *
	 * @param string  $smiley   A smiley
	 * @param Boolean $isXhtml  Ask for respected the xHtml standard code
	 * @return string
	 */
	protected function render($smiley, $isXhtml = true) {
		if (!isset($this->_map[$smiley])) {
			return '';
		}

		$path = sprintf('%s%s.%s',
			$this->config('path'),
			$this->_map[$smiley],
			$this->config('extension'));

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
		$smiley = trim($matches[0]);

		if (count($matches) === 1 || !$this->hasSmiley($smiley)) {
			return $matches[0];
		}

		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';

		if ($this->getParser()->config('xhtmlOutput')) {
			$image = $this->render($smiley, true);
		} else {
			$image = $this->render($smiley, false);
		}

		return $l . $image . $r;
	}

}

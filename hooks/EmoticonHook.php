<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\hooks;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\hooks\HookAbstract;

/**
 * Converts smiley faces into emoticon images.
 *
 * @package	mjohnson.decoda.hooks
 */
class EmoticonHook extends HookAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'path' => '/images/',
		'extension' => 'png'
	);

	/**
	 * Mapping of emoticons and smilies.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_emoticons = array();

	/**
	 * Map of smilies to emoticons.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_map = array();

	/**
	 * Parse out the emoticons and replace with images.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		if ($this->_emoticons) {
			foreach ($this->_emoticons as $smilies) {
				foreach ($smilies as $smile) {
					$content = preg_replace_callback('/(^|\n|\s)?' . preg_quote($smile, '/') . '(\n|\s|$)?/is', array($this, '_emoticonCallback'), $content);
				}
			}
		}

		return $content;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $parser
	 * @return \mjohnson\decoda\hooks\EmoticonHook
	 * @chainable
	 */
	public function setParser(Decoda $parser) {
		parent::setParser($parser);

		foreach ($parser->getPaths() as $path) {
			if (!file_exists($path . 'emoticons.json')) {
				continue;
			}

			if ($emoticons = json_decode(file_get_contents($path . 'emoticons.json'), true)) {
				foreach ($emoticons as $emoticon => $smilies) {
					foreach ($smilies as $smile) {
						$this->_map[$smile] = $emoticon;
					}
				}

				$this->_emoticons = array_merge($this->_emoticons, $emoticons);
			}
		}

		return $this;
	}

	/**
	 * Callback for smiley processing.
	 *
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function _emoticonCallback($matches) {
		$smiley = trim($matches[0]);

		if (count($matches) === 1 || !isset($this->_map[$smiley])) {
			return $matches[0];
		}

		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';

		$path = sprintf('%s%s.%s',
			$this->config('path'),
			$this->_map[$smiley],
			$this->config('extension'));

		if ($this->getParser()->config('xhtml')) {
			$image = '<img src="%s" alt="" />';
		} else {
			$image = '<img src="%s" alt="">';
		}

		return $l . sprintf($image, $path) . $r;
	}

}

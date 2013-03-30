<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Decoda;
use Decoda\Loader\FileLoader;

/**
 * Censors words found within the censored.txt blacklist.
 */
class CensorHook extends AbstractHook {

	/**
	 * List of words to censor.
	 *
	 * @var array
	 */
	protected $_blacklist = array();

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'suffix' => array('ing', 'in', 'er', 'r', 'ed', 'd')
	);

	/**
	 * Read the contents of the loaders into the emoticons list.
	 */
	public function startup() {
		if ($this->_blacklist) {
			return;
		}

		// Load files from config paths
		foreach ($this->getParser()->getPaths() as $path) {
			foreach (glob($path . 'censored.*') as $file) {
				$this->addLoader(new FileLoader($file));
			}
		}

		// Load the contents into the blacklist
		foreach ($this->getLoaders() as $loader) {
			$loader->setParser($this->getParser());

			if ($blacklist = $loader->load()) {
				$this->blacklist($blacklist);
			}
		}
	}

	/**
	 * Parse the content by censoring blacklisted words.
	 *
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return $this->_censor($content);
	}

	/**
	 * Parse the content by censoring blacklisted words.
	 *
	 * @param string $content
	 * @return string
	 */
	public function beforeStrip($content) {
		return $this->_censor($content);
	}

	/**
	 * Add words to the blacklist.
	 *
	 * @param array $words
	 * @return \Decoda\Hook\CensorHook
	 */
	public function blacklist(array $words) {
		$this->_blacklist = array_map('trim', array_filter($words)) + $this->_blacklist;
		$this->_blacklist = array_unique($this->_blacklist);

		return $this;
	}

	/**
	 * Return the current blacklist.
	 *
	 * @return array
	 */
	public function getBlacklist() {
		return $this->_blacklist;
	}

	/**
	 * Trigger censoring.
	 *
	 * @param string $content
	 * @return string
	 */
	protected function _censor($content) {
		foreach ($this->getBlacklist() as $word) {
			$content = preg_replace_callback('/(^|\s|\n|[^\w]){1,1}(?:' . $this->_prepareRegex($word) . ')([^\w]|\s|\n|$){1,1}/isS', array($this, '_censorCallback'), $content);
		}

		return $content;
	}

	/**
	 * Censor a word if its only by itself.
	 *
	 * @param array $matches
	 * @return string
	 */
	protected function _censorCallback($matches) {
		if (count($matches) === 1) {
			return $matches[0];
		}

		$length = mb_strlen(trim($matches[0]));
		$censored = '';
		$symbols = str_shuffle('*@#$*!?%');
		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';
		$i = 0;
		$s = 0;

		while ($i < $length) {
			$censored .= $symbols[$s];

			$i++;
			$s++;

			if ($s > 7) {
				$s = 0;
			}
		}

		return $l . $censored . $r;
	}

	/**
	 * Prepare the regex pattern for each word.
	 *
	 * @param string $word
	 * @return string
	 */
	protected function _prepareRegex($word) {
		$letters = str_split($word);
		$regex = '';

		foreach ($letters as $letter) {
			$regex .= preg_quote($letter, '/') . '+';
		}

		$suffix = $this->getConfig('suffix');

		if (is_array($suffix)) {
			$suffix = implode('|', $suffix);
		}

		$regex .= '(?:' . $suffix . ')?';

		return $regex;
	}

}

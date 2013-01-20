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
 * Censors words found within the censored.txt blacklist.
 */
class CensorHook extends AbstractHook {

	/**
	 * List of words to censor.
	 *
	 * @var array
	 */
	protected $_censored = array();

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'suffix' => array('ing', 'in', 'er', 'r', 'ed', 'd')
	);

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
		$this->_censored = array_map('trim', array_filter($words)) + $this->_censored;
		$this->_censored = array_unique($this->_censored);

		return $this;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @param \Decoda\Decoda $parser
	 * @return \Decoda\Hook\CensorHook
	 */
	public function setParser(Decoda $parser) {
		parent::setParser($parser);

		foreach ($parser->getPaths() as $path) {
			if (file_exists($path . '/censored.txt')) {
				$this->blacklist(file($path . '/censored.txt'));
			}
		}

		return $this;
	}

	/**
	 * Censor a word if its only by itself.
	 *
	 * @param array $matches
	 * @return string
	 */
	protected function _callback($matches) {
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
	 * Trigger censoring.
	 *
	 * @param string $content
	 * @return string
	 */
	protected function _censor($content) {
		if ($this->_censored) {
			foreach ($this->_censored as $word) {
				$content = preg_replace_callback('/(^|\s|\n|[^\w]){1,1}(?:' . $this->_prepare($word) . ')([^\w]|\s|\n|$){1,1}/isS', array($this, '_callback'), $content);
			}
		}

		return $content;
	}

	/**
	 * Prepare the regex pattern for each word.
	 *
	 * @param string $word
	 * @return string
	 */
	protected function _prepare($word) {
		$letters = str_split($word);
		$regex = '';

		foreach ($letters as $letter) {
			$regex .= preg_quote($letter, '/') . '+';
		}

		$suffix = $this->config('suffix');

		if (is_array($suffix)) {
			$suffix = implode('|', $suffix);
		}

		$regex .= '(?:' . $suffix . ')?';

		return $regex;
	}

}

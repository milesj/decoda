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
 * Censors words found within the censored.txt blacklist.
 *
 * @package	mjohnson.decoda.hooks
 */
class CensorHook extends HookAbstract {

	/**
	 * List of words to censor.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_censored = array();

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'suffix' => array('ing', 'in', 'er', 'r', 'ed', 'd')
	);

	/**
	 * Parse the content by censoring blacklisted words.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return $this->_censor($content);
	}

	/**
	 * Parse the content by censoring blacklisted words.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeStrip($content) {
		return $this->_censor($content);
	}

	/**
	 * Add words to the blacklist.
	 *
	 * @access public
	 * @param array $words
	 * @return \mjohnson\decoda\hooks\CensorHook
	 * @chainable
	 */
	public function blacklist(array $words) {
		$this->_censored = array_map('trim', array_filter($words)) + $this->_censored;
		$this->_censored = array_unique($this->_censored);

		return $this;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param \mjohnson\decoda\Decoda $parser
	 * @return \mjohnson\decoda\hooks\CensorHook
	 * @chainable
	 */
	public function setParser(Decoda $parser) {
		parent::setParser($parser);

		foreach ($parser->getPaths() as $path) {
			if (file_exists($path . 'censored.txt')) {
				$this->blacklist(file($path . 'censored.txt'));
			}
		}

		return $this;
	}

	/**
	 * Censor a word if its only by itself.
	 *
	 * @access protected
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
	 * @access protected
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
	 * @access protected
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

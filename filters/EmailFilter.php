<?php
/**
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2012, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\filters;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\filters\FilterAbstract;

/**
 * Provides tags for emails. Will obfuscate emails against bots.
 *
 * @package	mjohnson.decoda.filters
 */
class EmailFilter extends FilterAbstract {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'encrypt' => true
	);

	/**
	 * Supported tags.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_tags = array(
		'email' => array(
			'htmlTag' => 'a',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'escapeAttributes' => false,
			'attributes' => array(
				'default' => true
			)
		),
		'mail' => array(
			'htmlTag' => 'a',
			'displayType' => Decoda::TYPE_INLINE,
			'allowedTypes' => Decoda::TYPE_NONE,
			'escapeAttributes' => false,
			'attributes' => array(
				'default' => true
			)
		)
	);

	/**
	 * Encrypt the email before parsing it within tags.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function parse(array $tag, $content) {
		if (empty($tag['attributes']['default'])) {
			$email = $content;
			$default = false;
		} else {
			$email = $tag['attributes']['default'];
			$default = true;
		}

		// Return an invalid email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return $content;
		}

		$encrypted = '';

		if ($this->config('encrypt')) {
			$length = strlen($email);

			if ($length > 0) {
				for ($i = 0; $i < $length; ++$i) {
					$encrypted .= '&#' . ord(substr($email, $i, 1)) . ';';
				}
			}
		} else {
			$encrypted = $email;
		}

		$tag['attributes']['href'] = 'mailto:' . $encrypted;

		if ($this->getParser()->config('shorthandLinks')) {
			$tag['content'] = $this->message('mail');

			return '[' . parent::parse($tag, $content) . ']';
		}

		if (!$default) {
			$tag['content'] = $encrypted;
		}

		return parent::parse($tag, $content);
	}

	/**
	 * Strip a node but keep the email regardless of location.
	 *
	 * @access public
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function strip(array $tag, $content) {
		$email = isset($tag['attributes']['default']) ? $tag['attributes']['default'] : $content;

		return parent::strip($tag, $email);
	}

}
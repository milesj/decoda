<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace Decoda\Filter;

use Decoda\Decoda;

/**
 * Provides tags for emails. Will obfuscate emails against bots.
 */
class EmailFilter extends AbstractFilter {

	/**
	 * Configuration.
	 *
	 * @var array
	 */
	protected $_config = array(
		'encrypt' => true
	);

	/**
	 * Supported tags.
	 *
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

		if ($this->getConfig('encrypt')) {
			$length = mb_strlen($email);

			if ($length > 0) {
				for ($i = 0; $i < $length; ++$i) {
					$encrypted .= '&#' . ord(mb_substr($email, $i, 1)) . ';';
				}
			}
		} else {
			$encrypted = $email;
		}

		$tag['attributes']['href'] = 'mailto:' . $encrypted;

		if ($this->getParser()->getConfig('shorthandLinks')) {
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
	 * @param array $tag
	 * @param string $content
	 * @return string
	 */
	public function strip(array $tag, $content) {
		$email = isset($tag['attributes']['default']) ? $tag['attributes']['default'] : $content;

		return parent::strip($tag, $email);
	}

}
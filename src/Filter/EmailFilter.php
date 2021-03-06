<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
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
    protected $_config = [
        'encrypt' => true
    ];

    /**
     * Supported tags.
     *
     * @var array
     */
    protected $_tags = [
        'email' => [
            'htmlTag' => 'a',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_NONE,
            'escapeAttributes' => false,
            'attributes' => [
                'default' => true
            ]
        ],
        'mail' => [
            'aliasFor' => 'email'
        ]
    ];

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

<?php
/**
 * @copyright   2017, RIGAUDIE David - http://rigaudie.fr
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 */
namespace Decoda\Filter;
use Decoda\Decoda;
/**
 * Provides tags for script link.
 */
class ScriptFilter extends AbstractFilter {
    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'script' => array(
            'htmlTag' => 'script',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_NONE,
            'attributes' => array(
                'src' => self::WILDCARD,
            )
        )
    );

    /**
     * Check the tag script
     *
     * @param array $tag
     * @param string $content
     * @return string
     */
    public function parse(array $tag, $content) {
        // If more than 1 http:// is found in the string, possible XSS attack
        if ((mb_substr_count($tag['attributes']['src'], 'http://') + mb_substr_count($tag['attributes']['src'], 'https://')) > 1) {
            return null;
        }
        return parent::parse($tag, $content);
    }
}
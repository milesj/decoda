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
        if (substr($tag['attributes']['src'], 0, 2) != '..'
            && substr($tag['attributes']['src'], 0, 2) != '//'
            && substr($tag['attributes']['src'], 0, 2) != './') {
            if( filter_var($tag['attributes']['src'], FILTER_VALIDATE_URL) === FALSE) 
                return null;
        }
        return parent::parse($tag, $content);
    }
}
<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Hook;

use Decoda\Hook\AbstractHook;

/**
 * Converts URLs and emails (not wrapped in tags) into clickable links.
 */
class ClickableHook extends AbstractHook {

    /**
     * Matches a link or an email, and converts it to an anchor tag.
     *
     * @param string $content
     * @return string
     */
    public function beforeParse($content) {
        $parser = $this->getParser();

        if ($parser->hasFilter('Url')) {
            $protocols = $parser->getFilter('Url')->getConfig('protocols');
            $chars = preg_quote('-_=+|\;:&?/[]%,.!@#$*(){}"\'', '/');

            $pattern = implode('', array(
                '(' . implode('|', $protocols) . ')s?:\/\/', // protocol
                '([\w\.\+]+:[\w\.\+]+@)?', // login
                '([\w\.]{5,255}+)', // domain, tld
                '(:[0-9]{0,6}+)?', // port
                '([a-z0-9' . $chars . ']+)?', // query
                '(#[a-z0-9' . $chars . ']+)?' // fragment
            ));

            // We replace only links that are "standalone", not inside BB Code tags.
            // For example, neither [url="http://www.example.com"] nor [url]http://www.example.com[/url] will be replaced.
            $content = preg_replace_callback('/(?<![="\]])(' . $pattern . ')/is', array($this, '_urlCallback'), $content);
        }

        // Based on W3C HTML5 spec: https://www.w3.org/TR/html5/forms.html#valid-e-mail-address
        if ($parser->hasFilter('Email')) {
            $pattern = '/([a-z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?(?:\.[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?)*)/i';

            $content = preg_replace_callback($pattern, array($this, '_emailCallback'), $content);
        }

        return $content;
    }

    /**
     * Callback for email processing.
     *
     * @param array $matches
     * @return string
     */
    protected function _emailCallback($matches) {
        return $this->getParser()->getFilter('Email')->parse(array(
            'tag' => 'email',
            'attributes' => array()
        ), trim($matches[1]));
    }

    /**
     * Callback for URL processing.
     *
     * @param array $matches
     * @return string
     */
    protected function _urlCallback($matches) {
        return $this->getParser()->getFilter('Url')->parse(array(
            'tag' => 'url',
            'attributes' => array()
        ), trim($matches[1]));
    }

}

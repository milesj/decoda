<?php
/**
 * @copyright   2006-2014, Miles Johnson - http://milesj.me
 * @license     https://github.com/milesj/decoda/blob/master/license.md
 * @link        http://milesj.me/code/php/decoda
 */

namespace Decoda\Engine;

use Decoda\Exception\IoException;

/**
 * Renders tags by using PHP as template engine.
 */
class PhpEngine extends AbstractEngine {

    /**
     * Renders the tag by using PHP templates.
     *
     * @param array $tag
     * @param string $content
     * @return string
     * @throws \Decoda\Exception\IoException
     */
    public function render(array $tag, $content) {
        $setup = $this->getFilter()->getTag($tag['tag']);

        foreach ($this->getPaths() as $path) {
            $template = sprintf('%s%s.php', $path, $setup['template']);

            if (file_exists($template)) {
                extract($tag['attributes'], EXTR_OVERWRITE);
                ob_start();

                include $template;

                return trim(ob_get_clean());
            }
        }

        throw new IoException(sprintf('Template file %s does not exist', $setup['template']));
    }

}
<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\CodeFilter());
$code->addHook(new \Decoda\Hook\EmoticonHook());
$code->addHook(new\Decoda\Hook\CensorHook()); ?>

<h2>Code</h2>

<?php $string = "[code]// Constants
define('DECODA', __DIR__ . '/');

// Includes
spl_autoload_register();
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    DECODA
)));[/code]";

$code->reset($string);
echo $code->parse(); ?>

<h2>Code <span>with filters and hooks</span></h2>

<?php $string = "[code]email@domain.com

:] :) :D :/ >[ :p :o >_>

:happy: :aw: :cool: :kiss: :meh: :mmf: :heart:

fuuCCkk shhiiiitt bITCH assHOLE peeniiss douchhe

fucker shiting bitched[/code]";

$code->reset($string);
echo $code->parse(); ?>

<h2>Code <span>with language attribute</span></h2>

<?php $string = '[code="php"]<?php
abstract class HookAbstract implements Hook {

    /**
     * Return a message string from the parser.
     *
     * @param string $key
     * @param array $vars
     * @return string
     */
    public function message($key, array $vars = array()) {
        return $this->getParser()->message($key, $vars);
    }

} ?>[/code]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Code <span>with row highlights attribute</span></h2>

<?php $string = '[code hl="1,15"]<?php
abstract class FilterAbstract implements Filter {

    /**
     * Return a tag if it exists, and merge with defaults.
     *
     * @param string $tag
     * @return array
     */
    public function tag($tag) {
        $defaults = $this->_defaults;
        $defaults[\'key\'] = $tag;

        if (isset($this->_tags[$tag])) {
            return $this->_tags[$tag] + $defaults;
        }

        return $defaults;
    }

} ?>[/code]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Code <span>with Decoda markup</span></h2>

<?php $string = '[code][b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s][/code]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Var</h2>

<?php $string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. [var]Donec[/var] volutpat tellus vulputate dui venenatis quis euismod turpis pellentesque. Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien. Quisque viverra feugiat purus, [var]eu mollis felis condimentum id[/var]. In luctus faucibus felis eget viverra. Vivamus et velit orci. In in tellus mauris, at fermentum diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed a magna nunc, vel tempor magna. Nam dictum, arcu in pretium varius, libero enim hendrerit nisl, et commodo enim sapien eu augue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Suspendisse potenti. Proin tempor porta porttitor. Nullam a malesuada arcu.';

$code->reset($string);
echo $code->parse(); ?>
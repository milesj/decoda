<?php
$code = new Decoda();
$code->addFilter(new CodeFilter());
$code->addHook(new EmoticonHook());
$code->addHook(new CensorHook()); ?>

<h2>Code</h2>

<?php $string = "[code]// Constants
define('DECODA', dirname(__FILE__) .'/');
define('DECODA_HOOKS', DECODA .'hooks/');
define('DECODA_CONFIG', DECODA .'config/');
define('DECODA_FILTERS', DECODA .'filters/');
define('DECODA_TEMPLATES', DECODA .'templates/');
define('DECODA_EMOTICONS', DECODA .'emoticons/');

// Includes
spl_autoload_register();
set_include_path(implode(PATH_SEPARATOR, array(
	get_include_path(),
	DECODA, DECODA_HOOKS,
	DECODA_CONFIG, DECODA_FILTERS,
	DECODA_TEMPLATES, DECODA_EMOTICONS
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
abstract class DecodaHook extends DecodaAbstract {

	/**
	 * Parse the given content before the primary parse.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		return $content;
	}

	/**
	 * Parse the given content after the primary parse.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function afterParse($content) {
		return $content;
	}

} ?>[/code]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Code <span>with row highlights attribute</span></h2>

<?php $string = '[code hl="1,15"]<?php
abstract class DecodaAbstract {

	/**
	 * Parent Decoda object.
	 *
	 * @access protected
	 * @var Decoda
	 */
	protected $_parser;

	/**
	 * Return the Decoda parser.
	 *
	 * @access public
	 * @return Decoda
	 */
	public function getParser() {
		return $this->_parser;
	}

	/**
	 * Set the Decoda parser.
	 *
	 * @access public
	 * @param Decoda $parser
	 * @return void
	 */
	public function setParser(Decoda $parser) {
		$this->_parser = $parser;
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
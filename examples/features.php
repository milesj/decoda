
<h2>Localization</h2>

<?php
$code = new Decoda('[b]Features[/b]');
$code->addFilter(new QuoteFilter());

$code->setLocale('de-de');
echo 'German: '. $code->message('quoteBy') .'<br>';

$code->setLocale('fr-fr');
echo 'French: '. $code->message('quoteBy') .'<br>';

$code->setLocale('ko-kr');
echo 'Korean: '. $code->message('quoteBy') .'<br>';  ?>

<h2>Whitelisting tags</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->whitelist('b', 'i')->addFilter(new DefaultFilter());
echo $code->parse(); ?>

<h2>Disabling hooks or filters</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->disableHooks()->disableFilters();
echo $code->parse(); ?>

<h2>Disable tag parsing</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->defaults()->disable();
echo $code->parse(); ?>

<h2>Customizable brackets</h2>

<?php $string = '{b}Bold{/b}
{i}Italics{/i}
{u}Underline{/u}
{s}Strike through{/s}
{b}{i}{u}Bold, italics, underline{/u}{/i}{/b}';

$code = new Decoda($string);
$code->addFilter(new DefaultFilter())->setBrackets('{', '}');
echo $code->parse(); ?>

<h2>Shorthand emails and URLs</h2>

<?php $string = '[email]email@domain.com[/email]
[url]http://domain.com/[/url]';

$code = new Decoda($string);
$code->addFilter(new EmailFilter())->addFilter(new UrlFilter())->setShorthand();
echo $code->parse(); ?>

<h2>XHTML markup</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->addFilter(new DefaultFilter())->setXhtml();
echo $code->parse(); ?>
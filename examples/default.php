<?php
$code = new \mjohnson\decoda\Decoda();
$code->addFilter(new \mjohnson\decoda\filters\DefaultFilter()); ?>

<h2>Bold, italics, underline, strike-through</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Super-script and sub-script</h2>

<?php $string = 'Super[sup]script[/sup]
Sub[sub]script[/sub]
[sup]Super[/sup]-[sub]sub[/sub]-script';

$code->reset($string);
echo $code->parse(); ?>

<h2>Bold, italics <span>(XHTML)</span></h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code->setXhtml();
$code->reset($string);
echo $code->parse(); ?>
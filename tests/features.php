
<h2>Localization</h2>

<?php 
$code = new Decoda('[b]Test[/b]', array('locale' => 'de-de'));
echo 'German: '. $code->message('quoteBy') .'<br>';

$code = new Decoda('[b]Test[/b]', array('locale' => 'fr-fr'));
echo 'French: '. $code->message('quoteBy') .'<br>';

$code = new Decoda('[b]Test[/b]', array('locale' => 'ko-kr'));
echo 'Korean: '. $code->message('quoteBy') .'<br>';  ?>

<h2>Whitelisting tags</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->addFilter(new DefaultFilter())->whitelist('b', 'i');
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

$code = new Decoda($string, array('disabled' => true));
echo $code->parse(); ?>

<h2>Whitelisting</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[b][i][u]Bold, italics, underline[/u][/i][/b]';

$code = new Decoda($string);
$code->addFilter(new DefaultFilter())->whitelist('b', 'i');
echo $code->parse(); ?>
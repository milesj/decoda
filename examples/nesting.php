<h2>Unclosed tags</h2>

<?php $string = '[b]Bold
[i]Italics[/i]
[u]Underline[/b]
Strike through[/s]';

$code = new \Decoda\Decoda($string);
$code->addFilter(new \Decoda\Filter\DefaultFilter());
echo $code->parse(); ?>

<h2>Incorrectly nested</h2>

<?php $string = '[b]Bold[/b]
[b][i]Bold, italics[/i][/b]
[i][u]Bold, italics, underline (wrong)[/b][/i][/u]';

$code = new \Decoda\Decoda($string);
$code->addFilter(new \Decoda\Filter\DefaultFilter());
echo $code->parse(); ?>

<h2>Incorrectly nested hierarchy</h2>

<?php $string = '[li]List item outside of a list or olist.[/li]

[list]
[b]Bold tag as the first descendant of list.[/b]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante [b]ipsum primis in faucibus orci luctus[/b] et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed [i]tellus[/i]. Donec id dolor.[/li]
[/list]';

$code = new \Decoda\Decoda($string);
$code->addFilter(new \Decoda\Filter\DefaultFilter());
$code->addFilter(new \Decoda\Filter\ListFilter());
echo $code->parse(); ?>
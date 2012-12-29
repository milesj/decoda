<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\ListFilter()); ?>

<h2>List</h2>

<?php $string = '[list]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed tellus. Donec id dolor.[/li]
[/list]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Ordered List</h2>

<?php $string = '[olist]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed tellus. Donec id dolor.[/li]
[/olist]';

$code->reset($string);
echo $code->parse(); ?>

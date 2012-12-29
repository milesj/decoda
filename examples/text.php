<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\TextFilter()); ?>

<h2>Font Family</h2>

<?php $string = '[font="Arial"]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[/font]
[font="Verdana"]Volutpat tellus vulputate dui venenatis quis euismod turpis pellentesque.[/font]
[font="Tahoma"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/font]
[font="Monospace, \'Lucida Sans\'"]Quisque viverra feugiat purus, in luctus faucibus felis eget viverra.[/font]
[font="Times"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/font]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Size <span>(10-29)</span></h2>

<?php $string = '[size="5"]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[/size]
[size="10"]Volutpat tellus vulputate dui venenatis quis euismod turpis pellentesque.[/size]
[size="19"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/size]
[size="27"]Quisque viverra feugiat purus, in luctus faucibus felis eget viverra.[/size]
[size="32"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/size]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Color</h2>

<?php $string = '[color="red"]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[/color]
[color="blue"]Volutpat tellus vulputate dui venenatis quis euismod turpis pellentesque.[/color]
[color="#f66"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/color]
[color="#ff0088"]Quisque viverra feugiat purus, in luctus faucibus felis eget viverra.[/color]
[color="#cccccc"]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/color]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Headers</h2>

<?php $string = '[h1]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[/h1]
[h2]Volutpat tellus vulputate dui venenatis quis euismod turpis pellentesque.[/h2]
[h3]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/h3]
[h4]Quisque viverra feugiat purus, in luctus faucibus felis eget viverra.[/h4]
[h5]Suspendisse sit amet ipsum eu odio sagittis ultrices at non sapien.[/h5]
[h6]Lorem ipsum dolor sit amet, consectetur adipiscing elit.[/h6]';

$code->reset($string);
echo $code->parse(); ?>
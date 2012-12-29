<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\ImageFilter()); ?>

<h2>Image</h2>

<?php $string = '[img]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Image <span>with width or height</span></h2>

<?php $string = '[img width="500"]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]
[img height="50"]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]
[img width="43%" height="50"]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Image <span>with fake URLs</h2>

<?php $string = '[img]http://www.google.com/some/fake/image[/img]
[img]google.com/some/fake/image.jg[/img]';

$code->reset($string);
echo $code->parse(); ?>
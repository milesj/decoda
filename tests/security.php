
<?php
$code = new Decoda();
$code->defaults(); ?>

<h2>XSS Protection</h2>

<?php $string = '<script>alert("I can use XSS");</script>
[b]<script>alert(document.cookie);</script>[/b]
[div class="javascript:alert(document);"]Attribute XSS prevention[/div]';

$code->reset($string);
echo $code->parse(); ?>

<?php
$code = new \Decoda\Decoda();
$code->defaults(); ?>

<h2>XSS Protection</h2>

<p>Any form of XSS injection will be escaped or removed from the final output; this includes any attribute beginning with javascript:.</p><br>

<?php $string = '<script>alert("I can use XSS");</script>
[b]<script>alert(document.cookie);</script>[/b]
[div class="javascript:alert(document);"]Attribute XSS prevention[/div]
[video="youtube" size="small"]"onload="alert(\'XSS\');" id="[/video]';

$code->reset($string);
echo $code->parse(); ?>

<h2>XSS Protection <span>within an image</span></h2>

<p>If an [img] tag attempts to generate an XSS attack by placing multiple HTTP calls in one tag, the tag will not be rendered.
    For example, the following URL will fail: [img]http://example.com/delete-account?image=http://example.com/image.jpg[/img]</p><br>

<?php $string = '[img]http://localhost/doSomething.php?image=http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]';

$code->reset($string);
echo $code->parse(); ?>
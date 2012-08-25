<h2>Clickable</h2>

<?php $string = 'Valid:
email@domain.com
http://domain.com

Invalid:
email@domain
www.domain.com';

$code = new \mjohnson\decoda\Decoda($string);
$code->addFilter(new \mjohnson\decoda\filters\EmailFilter());
$code->addFilter(new \mjohnson\decoda\filters\UrlFilter());
$code->addHook(new \mjohnson\decoda\hooks\ClickableHook());
echo $code->parse(); ?>
<h2>Clickable</h2>

<?php $string = 'Valid:
email@domain.com
http://domain.com

Invalid:
email@domain
www.domain.com';

$code = new \Decoda\Decoda($string);
$code->addFilter(new \Decoda\Filter\EmailFilter());
$code->addFilter(new \Decoda\Filter\UrlFilter());
$code->addHook(new \Decoda\Hook\ClickableHook());
echo $code->parse(); ?>
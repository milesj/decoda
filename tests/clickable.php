
<h2>Clickable</h2>

<?php $string = 'Valid:
email@domain.com
http://domain.com

Invalid:
email@domain
www.domain.com';

$code = new Decoda($string);
echo $code->parse(); ?>
<h2>Emoticons</h2>

<?php $string = 'Decoda also comes with an emoticon system. It will convert any kind of smiley from emoticons.json into a small image.
The system will not parse smilies within strings (like URLs) that resemble smilies. Here are a couple:

:] :) :D :/ >[ :p :o >_>

It also supports the word syntax:

:happy: :aw: :cool: :kiss: :meh: :mmf: :heart:';

$code = new \Decoda\Decoda($string);
$code->addFilter(new \Decoda\Filter\ImageFilter());
$code->addHook(new \Decoda\Hook\EmoticonHook(array('path' => '../emoticons/')));
echo $code->parse(); ?>
<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\VideoFilter()); ?>

<h2>Video</h2>

<?php $string = '[video="youtube"]piZrjDTx2eg[/video]
[vimeo]27315673[/vimeo]
[liveleak]d4a_1313688628[/liveleak]
[veoh]v21205329j6GXPXhT[/veoh]
[dailymotion]xklaf6_gamescom-2011_videogames[/dailymotion]
[myspace]108061717[/myspace]
[wegame]World_of_Workcraft[/wegame]
[collegehumor]6450423[/collegehumor]
[vevo]USUV71301250[/vevo]
[funnyordie]d182501dfe[/funnyordie]';

$code->reset($string);
echo $code->parse(); ?>

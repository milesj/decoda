<?php
$code = new \mjohnson\decoda\Decoda();
$code->addFilter(new \mjohnson\decoda\filters\VideoFilter()); ?>

<h2>Video</h2>

<?php $string = '[video="youtube"]piZrjDTx2eg[/video]
[video="vimeo"]27315673[/video]
[video="liveleak"]d4a_1313688628[/video]
[video="veoh"]v21205329j6GXPXhT[/video]
[video="dailymotion"]xklaf6_gamescom-2011_videogames[/video]
[video="myspace"]108061717[/video]
[video="wegame"]World_of_Workcraft[/video]
[video="collegehumor"]6450423[/video]';

$code->reset($string);
echo $code->parse(); ?>

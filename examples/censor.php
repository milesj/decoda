<h2>Censoring</h2>

<?php $string = 'Decoda uses a pretty awesome censoring system. It censors any word in the censored.txt blacklist file.
It tries its best not to censor words within other words, but it can happen. Here are a few examples:

fuck shit bitch asshole penis douche

And here are the same words, but with different lengths for each letter, and in different cases.

fuuCCkk shhiiiitt bITCH assHOLE peeniiss douchhe

It will also magically censor words that end with: ing, ed, er

fucker shiting bitched

Hope it works out, I tried to make it as smart as possible.';

$code = new \mjohnson\decoda\Decoda($string);
$code->addHook(new \mjohnson\decoda\hooks\CensorHook());
echo $code->parse(); ?>
<h2>Censoring</h2>

<?php $string = 'slut fuck bitch

Decoda uses a pretty awesome censoring system. It censors any word in the censored.txt blacklist file.
It tries its best not to censor words within other words, but it can happen. Here are a few examples:

fuck shit bitch asshole penis douche

And here are the same words, but with different lengths for each letter, and in different cases.

fuuCCkk shhiiiitt! bITCH assHOLE peeniiss douchhe

It will also magically censor words that end with: ing, ed, er

fucker shiting bitched?

Lets try not to censor words that are already part of other words.

analyst cockle nigeria grape

Hope it works out, I tried to make it as smart as possible.

fuck. cunt';

$code = new \Decoda\Decoda($string);
$code->addHook(new \Decoda\Hook\CensorHook());
echo $code->parse(); ?>
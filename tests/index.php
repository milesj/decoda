<?php
error_reporting(E_ALL);
function debug($var) {
	echo '<pre>'. print_r($var, true) .'</pre>';
}
include '../Decoda.php'; ?>

<!DOCTYPE html>
<html>
<head>
<title>Decoda Examples</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>Decoda Examples</h1>

<?php $code = new Decoda('Copyright 2009-'. date('Y') .' [sup]&copy;[/sup] Miles Johnson - [url]http://milesj.me[/url]');
echo $code->parse();

include 'url.php'; ?>



<h2>Basic Styles</h2>

<?php $string = '[b]Bold[/b]
[i]Italics[/i]
[u]Underline[/u]
[s]Strike through[/s]
[color="#f00"]Red Text (Hex code)[/color]
[color="purple"]Purple Text (Name)[/color]
[h3]Header 3[/h3]
[h6]Header 6[/h6]
[size="12"]Font size 12[/size]
[size="24"]Font size 24[/size]
Sub[sub]Script[/sub]
Super[sup]Script[/sup]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>URLs and Emails</h2>

<?php $string = '[email]email@domain.com[/email]
[email="email@domain.com"]Linked email[/email]
email@domain.com
email+hash@sub.domain.com
[url]http://domain.com[/url]
[url="http://domain.com"]Linked URL[/url]
[url]https://securesite.com[/url]
[url]ftp://ftpsite.com[/url]
[url]irc://ircsite.com[/url]
[url]mwj://unsupportedprotocol.com[/url] (Should not link)
[url]www.domain.com[/url] (Should not link)
http://domain.com
http://sub.domain.com/?with=param
http://user:pass@domain.com:80/?with=param';
$code = new Decoda($string);
$code->addHook(new ClickableHook());
echo $code->parse(); ?>

<h2>Emoticons</h2>

<?php 
$code = new Decoda(':) ;[ :D :O <3 :/ :aw: :angry:');
$code->addHook(new EmoticonHook());
echo $code->parse(); ?>

<h2>Text Styles</h2>

<?php $string = '[b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. [i]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;[/i] Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. [u]Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu[/u], eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Font Styles</h2>

<?php $string = '[font="Verdana"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. [color=red]Aliquam laoreet pulvinar sem. Aenean at odio.[/color] Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/font]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Alignment</h2>

<?php $string = '[align="center"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.[/align]
[align="justify"]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/align]
[align="right"]Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/align]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Div Blocks</h2>

<?php $string = '[div]No attributes.[/div] 
[div id="divBlock"]With an ID.[/div]
[div class="div"]With a class.[/div]
[div id="customId" class="div secondary" data-attr="html5"]Uses multiple random attributes.[/div] 
[note]This is a note![/note]
[alert]This is an alert![/alert]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Quotes</h2>

<?php $string = '[quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Nested Quotes</h2>

<?php $string = '[quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    

Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.
    [quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. 
	
Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor.
	
Curabitur sed tellus. Donec id dolor.[/quote][/quote]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Spoilers</h2>

<?php $string = '[spoiler]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/spoiler]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Code Block</h2>

<?php $string = '[code]This is a [b][i][u]basic[/u][/i][/b] code block! 
Decoda mark-up is not converted inside code tags, excluding the code tag itself.[/code]

[code lang="php" hl="15"]/**
 * Apply configuration.
 *
 * @access public
 * @param string $options
 * @param bool $value
 * @return void
 */
public function configure($options, $value = true) {
    if (is_array($options)) {
        foreach ($options as $option => $value) {
            $this->configure($option, $value);
        }
    } else {
        if (!is_bool($value)) {
            return false;
        }

        if (isset($this->__config[$options])) {
            $this->__config[$options] = $value;
        }
    }
}[/code]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Lists</h2>

<?php $string = '[list]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed tellus. Donec id dolor.[/li]
[/list]
[olist]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed tellus. Donec id dolor.[/li]
[/olist]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Images</h2>

<?php $string = '[img]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]
[img width="175" height="50"]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]';
$code = new Decoda($string);
echo $code->parse(); ?>

<h2>Videos</h2>

<?php $code = new Decoda('[video="youtube"]snZLmaVmd2o[/video]');
echo $code->parse(); ?>

</body>
</html>
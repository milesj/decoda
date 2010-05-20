<?php // Turn on error reporting
error_reporting(E_ALL);

// Include Decoda
include('decoda.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Decoda Examples</title>
<style type="text/css">
    body { padding: 15px; font: normal 12px Arial, Tahoma, sans-serif; color: #000; }
    h2 { margin-top: 50px; }
    .decoda-quote { background: #FFFFCC; padding: 10px; }
    .decoda-quoteAuthor { font-weight: bold; margin-bottom: 5px; }
    .decoda-quoteDate { float: right; }
    .decoda-spoilerBody { background: #FFFFCC; padding: 10px; margin-top: 10px; }
    .decoda-code { background: #f4f4f4; padding: 10px; }
</style>
</head>
<body>

<h1>Decoda Examples</h1>

<?php $string = 'Copyright 2009-'. date('Y') .' [sup]&copy;[/sup] Miles Johnson - [url]http://milesj.me[/url]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Emoticons</h2>

<?php $string = ':) ;[ :D :O <3 :/ :aw: :angry:';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Text Styles</h2>

<?php $string = '[b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. [i]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;[/i] Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. [u]Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu[/u], eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Font Styles</h2>

<?php $string = '[font="Verdana"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. [color=red]Aliquam laoreet pulvinar sem. Aenean at odio.[/color] Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/font]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Alignment</h2>

<?php $string = '[align=center]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/align]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Quotes</h2>

<?php $string = '[quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Nested Quotes</h2>

<?php $string = '[quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.
    [quote="Miles" date="'. date('Y-m-d H:i:s') .'"]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio.
    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.
    Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote][/quote]';
$code = new Decoda($string);
$code->configure('childQuotes', true);
$code->parse(); ?>

<h2>Spoilers</h2>

<?php $string = '[spoiler]Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/spoiler]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Code Block</h2>

<?php $string = '[code lang="php" hl="15"]/**
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
$code->parse(); ?>

<h2>Lists</h2>

<?php $string = '[list]
[li]Lorem ipsum dolor sit amet, consectetuer adipiscing elit.[/li]
[li]Aliquam laoreet pulvinar sem. Aenean at odio.[/li]
[li]Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit.[/li]
[li]Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante.[/li]
[li]Curabitur tincidunt, lacus eget iaculis tincidunt.[/li]
[li]Curabitur sed tellus. Donec id dolor.[/li]
[/list]';
$code = new Decoda($string);
$code->parse(); ?>

<h2>Images</h2>

<?php $string = '[img]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]
    [img width=175 height=50]http://www.google.com/intl/en_ALL/images/srpr/logo1w.png[/img]';
$code = new Decoda($string);
$code->parse(); ?>

</body>
</html>

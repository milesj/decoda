<?php
/**
 * @copyright    Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license        http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link        http://milesj.me/code/php/decoda
 */

require_once '../tests/bootstrap.php';

// Build menus
$view = strtolower(isset($_GET['view']) ? $_GET['view'] : 'features');

$about = array(
    'features' => 'Features',
    'security' => 'Security',
    'nesting' => 'Invalid nesting',
    'engine' => 'Template Engine'
);

$filters = array(
    'default' => 'Default',
    'block' => 'Blocks',
    'code' => 'Code',
    'email' => 'Email',
    'image' => 'Images',
    'list' => 'Lists',
    'quote' => 'Quotes',
    'text' => 'Text and Font',
    'url' => 'URLs',
    'video' => 'Videos'
);

$hooks = array(
    'censor' => 'Word Censoring',
    'clickable' => 'Auto-clickable URLs and emails',
    'emoticon' => 'Emoticons'
);

function debug($var) {
    echo '<pre>'. print_r($var, true) .'</pre>';
}

function buildMenu($items, $view) {
    foreach ($items as $key => $item) {
        if ($view === $key) {
            echo '<a href="?view='. $key .'" class="active">'. $item .'</a>';
        } else {
            echo '<a href="?view='. $key .'">'. $item .'</a>';
        }
    }
} ?>

<!DOCTYPE html>
<html>
<head>
    <title>Decoda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        * { margin: 0; padding: 0; }
        body { padding: 50px; font: normal 13px/150% Arial, Tahoma, sans-serif; color: #000; }
        h1 { font-size: 32px; margin-bottom: 5px; }
        h2 { margin: 50px 0 15px 0; font-size: 24px; }
        dl { margin-top: 15px; }
        dl dt { float: left; font-weight: bold; text-align: right; clear: both; padding-top: 5px; }
        dl dd { float: left; padding-left: 10px; }
        dl a { background: aliceblue; color: slategray; display: inline-block; padding: 5px 10px; margin: 0 10px 10px 0; text-decoration: none; border-radius: 3px; }
        dl a:hover,
        dl a.active { background: skyblue; color: white; }
        var { background: beige; padding: 2px 5px; border-radius: 3px; }
        ul, ol { margin-left: 50px; }
        .clear { clear: both; display: block; }
        .float-right { float: right; }
        .float-left { float: left; }
        .align-left { text-align: left; }
        .align-right { text-align: right; }
        .align-center { text-align: center; }
        .align-justify { text-align: justify; }
        .decoda-quote { background: #FFFFCC; padding: 10px; margin: 0 0 15px 15px; border-radius: 3px; }
        .decoda-quote .decoda-quote { background: khaki; margin: 15px 0; }
        .decoda-quote .decoda-quote .decoda-quote { background: goldenrod; }
        .decoda-quote-head { font-weight: bold; margin-bottom: 5px; }
        .decoda-quote-date { float: right; }
        .decoda-code { background: lightgray; padding: 10px; border-radius: 3px; }
        .decoda-alert { background: lightpink; padding: 10px; border-radius: 3px; }
        .decoda-note { background: powderblue; padding: 10px; border-radius: 3px; }
        .decoda-spoiler-content { background: palegreen; margin-top: 5px; padding: 10px; border-radius: 3px; }
        .decoda-spoiler-content .decoda-spoiler { margin-top: 10px; }
        .decoda-spoiler-content .decoda-spoiler-content { background: oldlace; }
    </style>
</head>
<body>
    <h1>Decoda</h1>

    <?php // Copyright
    $code = new \Decoda\Decoda('Copyright 2009-' . date('Y') . ' [sup]&copy;[/sup] Miles Johnson - [url]http://milesj.me[/url]');
    $code->defaults();
    echo $code->parse(); ?>

    <dl>
        <dt>About</dt>
        <dd><?php buildMenu($about, $view); ?></dd>

        <dt>Filters</dt>
        <dd><?php buildMenu($filters, $view); ?></dd>

        <dt>Hooks</dt>
        <dd><?php buildMenu($hooks, $view); ?></dd>
    </dl>

    <span class="clear"></span>

    <?php if (file_exists($view . '.php') && $view !== 'index') {
        include $view . '.php';
    } else {
        include 'features.php';
    } ?>
</body>
</html>
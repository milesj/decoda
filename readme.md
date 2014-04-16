# Decoda v6.5.1 [![Build Status](https://travis-ci.org/milesj/decoda.png?branch=master)](https://travis-ci.org/milesj/decoda) #

A lightweight lexical string parser for BBCode styled markup.

## Requirements ##

* PHP 5.3.0
    * Multibyte
* Composer

## Compatibility ##

* v3.x - PHP 5.2
* v4.x - PHP 5.3
* v5.x - PHP 5.3, Composer

## Contributors ##

* "Marten-Plain" emoticons by Mårten Lundin - http://adiumxtras.com/index.php?a=xtras&xtra_id=6920
* "HTML_BBCodeParser" by Seth Price - http://pear.php.net/package/HTML_BBCodeParser/

## Features ##

* Parses custom code to valid (X)HTML markup
* Setting to make links and emails auto-clickable
* Setting to use shorthand text for links and emails
* Filters to parse markup and custom code
* Hooks to execute callbacks during the parsing cycle
* Loaders to load resources and files for configuration
* Engines to render complex markup using a template system
* Can censor offensive words
* Can convert smiley faces into images
* Basic support for localized messages
* Supports a wide range of tags
* Parent child node hierarchy
* Fixes incorrectly nested tags by removing the broken/unclosed tags
* Self closing tags
* Logs errors for validation
* Tag and attribute aliasing

## Filters ##

The following filters and supported tags are available.

* Default - b, i, u, s, sup, sub, br, hr, abbr, time
* Block - align, float, hide, alert, note, div, spoiler, left, right, center, justify
* Code - code, source, var
* Email - email, mail
* Image - image, img
* List - list, olist, li, *
* Quote - quote
* Text - font, size, color, h1-h6
* Url - url, link
* Video - video, youtube, vimeo, veoh, liveleak, dailymotion, myspace, wegame, collegehumor
* Table - table, thead, tbody, tfoot, tr, td, th, row, col

## Hooks ##

The following hooks are available.

* Censor - Censors all words found within config/censored
* Clickable - Converts all non-tag wrapped URLs and emails into clickable links
* Emoticon - Converts all smilies found within config/emoticons into emoticon images

## Documentation ##

Thorough documentation can be found here: http://milesj.me/code/php/decoda

A MooTools textarea editor is also available: https://github.com/milesj/decoda.js
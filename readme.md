# Decoda v3.0 ALPHA #

A stand alone lightweight, BBcode style parser class.

Todo:
* Child depth limitation
* Not converting linebreaks from rendered templates

## Requirements ##

* PHP 5.2.x

## Contributors ##

* "Marten-Plain" emoticons by Mårten Lundin - http://adiumxtras.com/index.php?a=xtras&xtra_id=6920

## Features ##

* Parses custom code to valid (X)HTML markup
* Setting to make links and emails auto-clickable
* Setting to use shorthand links and emails
* Implements the ability to censor words
* Support for adding additional user code
* Supports additional attributes for select tags
* Supports the following: bold, italics, underline, alignment, color, font, sup, sub, font size, h1-h6, code (pre), urls, emails, images, divs, lists, quotes, videos

## Unsupported ##

* URLs that begin with www and not http:// will not be converted (intentional)
* Certain videos are not supported as their embed code does not match the URL

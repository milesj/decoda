# Decoda v3.0 ALPHA #

A lightweight lexical string parser for BBCode styled markup.

Version(s) 3.x are not backwards compatible with 2.9 and lower. The newer versions were completely rewritten as a lexical parser that examines the string stack, where as the older versions were using archaic regex parsing. The newer versions also boast a very powerful filter and hook system, so your old code will need to be changed to support the newer functionality.

## Requirements ##

* PHP 5.2.x

## Contributors ##

* "Marten-Plain" emoticons by Mårten Lundin - http://adiumxtras.com/index.php?a=xtras&xtra_id=6920

## Features ##

* Parses custom code to valid (X)HTML markup
* Setting to make links and emails auto-clickable
* Setting to use shorthand text for links and emails
* Provides Filters to parse markup and custom code
* Provides Hooks to execute during the parsing cycle
* Provides functionality to render complex markup using a template system
* Can censor offensive words
* Can convert smiley faces into images
* Basic support for localized messages
* Supports the following filters and tags:

	Default - b, i, u, s, sup, sub
	Text - font, size, color, h1-h6
	Block - align, float, hide, alert, note, div, spoiler
	Code - code, var
	Email - email, mail
	URL - url, link
	Image - img, image
	List - list, olist, li
	Video - video
	Quote - quote

## Unsupported ##

* URLs that begin with www will not be converted (intentional)
* Certain videos are not supported as their embed code does not match the URL in the address bar

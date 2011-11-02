# Decoda v3.0 #

A lightweight lexical string parser for BBCode styled markup.

Version(s) 3.x are not backwards compatible with 2.9 and lower. The newer versions were completely rewritten as a lexical parser that examines the string stack, where as the older versions were using archaic regex parsing. The newer versions also boast a very powerful filter and hook system, so your old code will need to be changed to support the newer functionality.

## Requirements ##

* PHP 5.2.x

## Contributors ##

* "Marten-Plain" emoticons by Mårten Lundin - http://adiumxtras.com/index.php?a=xtras&xtra_id=6920
* "HTML_BBCodeParser" by Seth Price - http://pear.php.net/package/HTML_BBCodeParser/

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
* Supports a wide range of tags
* Fixes incorrectly nested tags

## Unsupported ##

* URLs that begin with www will not be converted (intentional)
* Certain videos are not supported as their embed code does not match the URL in the address bar

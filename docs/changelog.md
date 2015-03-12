# Changelog #

*These logs may be outdated or incomplete.*

## 6.5.2 ##

* Includes changes from previous versions
* Added a depth value to node extractions
* Fixed a bug with `aliasFor` where it would not inherit from defaults [[#70](https://github.com/milesj/decoda/issues/70)]
* Fixed a bug where whitespace within the tag would break rendering [[#69](https://github.com/milesj/decoda/issues/69)]

## 6.5.0 ##

* Added a filter aliasing system
* Added an attribute aliasing system
* Added colspan and rowspan attribute support for `[td]` and `[th]` tags
* Improved filter efficiency by compiling tags during construction

## 6.4.0 ##

* Added new `defaultProtocol` option to `UrlFilter`
* Add relative and absolute URL support [[#62](https://github.com/milesj/decoda/issues/62)]
* Updated URLs to allow non-URL content
* Updated URLs to be used without protocol
* Updated to not trim spaces within tags [[#63](https://github.com/milesj/decoda/pull/63)]
* Fixed a bug where errors were not reset [[#65](https://github.com/milesj/decoda/issues/65)]

## 6.3.0 ##

* Added Hungarian translations
* Added `removeEmpty` option that removes empty tags
* Updated to allow query strings and fragments in image URLs [[#57](https://github.com/milesj/decoda/issues/57)]

## 6.2.3 ##

* Fix self closing tags within other tags bug [[#52](https://github.com/milesj/decoda/issues/52)]
* Fix config not being inherited through constructor [[#11](https://github.com/milesj/decoda/issues/11)]

## 6.2.2 ##

* Includes changes from 6.2.1
* Added newline cleaning before and after parsing
* Fixed a bug where newlines will be converted multiple times [[#51](https://github.com/milesj/Decoda/pull/51)]
* Improved the `ClickableHook`

## 6.2.0 ##

* Added a newline to line break conversion setting `lineBreaks` [[#48](https://github.com/milesj/Decoda/issues/48)]
* Added an `onlyTags` setting to filters that only allow tags and no text nodes as direct descendants
* Added `[*]` list item tag to `ListFilter` (does not support nested lists)
* Changed utility methods to public from protected
* Improved newline normalization

## 6.1.0 ##

* Added custom exception classes
* Added `vevo` and `funnyordie` video support
* Moved non-class folders to the root of `src/`

## 6.0.5 ##

* Fixed child depth parsing issues when the parent shouldn't have a `maxChildDepth`

## 6.0.4 ##

* Updated [code] templates to be customizable through `CodeHook`

## 6.0.3 ##

* Added word boundaries to censoring in `CensorHook`

## 6.0.2 ##

* Improved smiley parsing in `EmoticonHook`

## 6.0.1 ##

* Fixed a bug where URL tags were rendering as self closing tags

## 6.0.0 ##

* Added a Component class which all Filters, Hooks, Engines and Loaders extend
* Added a Loader class to handle resource file loading for configuration and messages
* Added Hook::startup() to initialize data before callbacks are called
* Added Decoda::addMessages() to add messages using a Loader
* Added Decoda::getBlacklist() and getWhitelist()
* Added a 2nd argument $key for Decoda::addFilter() and addHook()
* Added a default attribute to ImageFilter (img="200x200")
* Added a default attribute to ListFilter (list="upper-roman")
* Added a new TableFilter
* Added custom exceptions
* Renamed all config methods to getConfig() and setConfig()
* Renamed Filter::tag() to getTag()
* Renamed Filter::tags() to getTags()
* Renamed Engine::setPath() to addPath()
* Renamed Engine::getPath() to getPaths()
* Updated CensorHook to support blacklisting words using a Loader
* Updated EmoticonHook to support adding emoticons using a Loader
* Updated Decoda::setLocale() so that it no longer throws exceptions (can now support setting the default locale)
* Updated Engines to support multiple template lookup paths
* Updated with Travis CI and phpunit.xml integration

## 5.1.1 ##

* Added multibyte check to composer

## 5.1.0 ##

* Updated to use Multibyte extensively
* Added Decoda::hasFilter() and Decoda::hasHook()
* Added ` tags within <pre> for  and proper semantics
* Added [var]

## 5.0.0 ##

* Changed folder and namespace structure to make heavier use of Composer
* Added attribute support for self closing tags
* Moved interfaces to source root
* Reversed naming of abstract classes
* Removed Decoda's autoloader in place of Composer
* Removed DECODA constant
* Updated to use SPL exceptions

## 4.1.1 ##

* Fixed attribute aliasing within Filter.mapAttributes
* Updated video URLs to be protocol agnostic [[Issue #29](https://github.com/milesj/php-decoda/issues/29)]
* Updated message translations

## 4.1.0 ##

* Added ,  support to DefaultFilter
* Added Decoda::strip() to strip tags instead of parse tags
* Added Hook::beforeStrip() and Hook::afterStrip()
* Added a max newlines feature and config
* Added Decoda::setMaxNewlines()
* Added vendor specific video tags
* Added support for parsing via method with the same name as the tag
* Refactored how Decoda::loadFile() works
* Refactored new line handling and normalization
* Removed ampersand from censoring
* Updated configuration support
* Fixed support for uppercase tags
* Fixed VideoFilter notice error
* Fixed censor greediness and improved logic
* Fixed Composer issues

## 4.0.0 ##

* Fixed namespace issues with Decoda::addFilter() and Decoda::addHook()

## 4.0.0-beta ##

* Refactored for PHP 5.3 namespaces and moved all classes to namespaced folders
* Added unit tests for all classes
* Added configuration paths to define custom lookup locations
* Added a new option for Filters: childrenBlacklist and renamed children to childrenWhitelist
* Added a new option for Filters: persistContent
* Added a new config for QuoteFilters: dateFormat
* Added a new config for BlockFilters: spoilerToggle
* Added a new config for UrlFilter: protocols
* Added a new config for CensorHook: suffix
* Added a new configs for EmoticonHook: path, extension
* Added support for self closing tags:
* 
* Added a global blacklist to Decoda using Decoda::blacklist()
* Fixed incorrectly nested tags
* Fixed child and parent hierarchies
* Fixed CRLF conversion problems
* Merged Filter options alias and map into mapAttributes
* Moved all class constants to Decoda base
* Refactored all Filter regex patterns
* Refactored all the template HTML classes
* Removed all global constants except for DECODA
* Removed Decoda::nl2br()
* Removed Filter option testNoDefault
* Renamed Filter option key to tag
* Renamed Filter option tag to htmlTag
* Renamed Filter option type to displayType
* Renamed Filter option allowed to allowedTypes
* Renamed Decoda::disableFilters() to resetFilters()
* Renamed Decoda::disableHooks() to resetHooks()
* Updated doc blocks and examples
* Updated EmailFilter and UrlFilter to use filter_var()
* Updated Decoda to throw exceptions when necessary

## 3.5 ##

* Added support for different template engines

## 3.4 ##

* Added Composer support
* Added an alias property to filters allowing attribute alias names for tags
* Added , ,  [[Issue #14](https://github.com/milesj/php-decoda/issues/14)]
* Added strict mode configuration which will allow attributes to be used without double quotes
* Added an echo argument to Decoda::parse()
* Replaced errors with exceptions
* Refactored to use strict equality

## 3.3.1 ##

* Fixed an XSS issue regarding  tags when passing multiple HTTP URLs
* Updated  to only accept http://, https:// and removed ftp://, file://
* Updated attribute parsing to exclude anything starting with javascript:

## 3.3 ##

* Added DecodaFilter::setupHooks() to allow filters to initialize hook dependencies
* Added DecodaHook::setupFilters() to allow hooks to initialize filter dependencies
* Added CodeHook (CodeFilter dependency) that stops emoticons from being processed in code blocks [[Issue #9](https://github.com/milesj/php-decoda/issues/9)]
* Check for class or interface during autoload [[Issue #10](https://github.com/milesj/php-decoda/issues/10)]
* Made HTML escaping a boolean setting [[Issue #11](https://github.com/milesj/php-decoda/issues/11)]
* Switched CensorHook::afterParse() to beforeParse()

## 3.2 ##

* Fixed XSS vulnerabilities
* Trimmed inner tag content to fix newline rendering problem
* Made disable() chainable
* Made whitelist() accept an array or multiple arguments
* Made Decoda::loadFile() public [[Issue #6](https://github.com/milesj/php-decoda/issues/6)]

## 3.1 ##

* Added a getErrors() method
* Added a defined() checks for constants
* Added a  tag to BlockFilter
* Added an error system to detect incorrectly nested tags, unclosed tags and invalid scope types
* Added a nl2br() method to Decoda
* Fixed func_get_args() bug for PHP 5.2
* Fixed a bug with nl2br() on PHP 5.2

## 3.0 ##

* Rewrote from the ground up as a lexical parser (Not backwards compatible)
* Added a powerful Filter and tag system
* Added a utility Hook system
* Implemented a templating system for complex tags
* Converted configuration to JSON format
* Removed GeSHI support

## 2.9 ##

* Added a config class to handle the loading of emoticons and censored words
* Altered quote parsing to only show the first child quote, and not all children
* Fixed some censoring bugs

## 2.8 ##

* Added new constants for specific paths
* Added an emoticon smiley system
* Added a childQuotes setting that removes or parses all child quotes
* Move all config options to the $config property and all methods into configure()
* Changed __cleanLinebreaks() to __cleanup()
* 

## 2.7 ##

* Changed CSS classes to use dashes
* Fixed a problem with quotes timestamps breaking
* Fixed a problem with unclosed or nested quotes
* Rewrote parse() and moved all patterns to $markupCode and $markupResult
* Added spoiler tags

## 2.6 ##

* Updated the PHP Doc blocks and variable, method names
* Added a censored words system, can add words with addCensored()
* Added a hide tag
* Changed the b, i and u tags to use the regular HTML tags

## 2.5 ##

* Added functionality for the GeSHi code highlighter
* Added an attributes() method to deal with element attributes
* Added the callbackCode(), processCode() and processGeshi() methods to manage the GeSHi implementation
* Added the setupGeshi() and useGeshi() methods to apply GeSHi settings
* Added a float markup type to float content left or right (must clear!)
* Rewrote the div tags to use double quotes
* Rewrote parse() to properly escape HTML
* Rewrote makeClickable() and useShorthand()

## 2.4 ##

* Added support for multi-byte and UTF-8 characters
* Fixed a few problems with processQuotes() not parsing or returning the correct result
* Fixed a bug with processLists()
* Rewrote removeCode() because it didn't work to begin with

## 2.3 ##

* Added an $allowed property to restrict what tags should be parsed
* Added an allowed() method to deal with the restricted tags
* Added a parseDefaults() method to deal with the $allowed
* Replaced the $markup property with 2 standalone properties to work in sync with the new allowed system
* Rewrote __construct(), addMarkup(), clickable() and parse()
* Removed the title and alt attributes from emails and urls
* Fixed an error with parse() not returning data when no  was found

## 2.2 ##

* First initial release of Decoda

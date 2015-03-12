# Decoda #

Decoda is a lightweight class that extracts and parses a custom markup language; based on the concept of BB code. Decoda supports all the basic HTML tags and manages special features for making links and emails auto-clickable, using shorthand emails and links, and finally allowing the user to add their own code tags.

Decoda is a play on words for: Decoding Markup.

* Parses custom code to valid (X)HTML markup
* Setting to make links and emails auto-clickable
* Setting to use shorthand text for links and emails
* Provides Filters to parse markup and custom code
* Provides Hooks to execute during the parsing cycle
* Provides Engines to render templates
* Provides functionality to render complex markup using a template system
* Can censor offensive words
* Can convert smiley faces into images
* Basic support for localized messages
* Supports a wide range of tags
* Fixes incorrectly nested tags by removing the broken/unclosed tags
* Logs errors for validation

## Installation ##

Install by manually downloading the library or defining a [Composer dependency](http://getcomposer.org/).

```javascript
{
    "require": {
        "mjohnson/decoda": "6.*"
    }
}
```

For each string that we want to parse, we instantiate a new Decoda object and pass the string to the constructor.

```php
$code = new Decoda\Decoda('Hello, my name is [b]Miles Johnson[/b], you may visit my website at [url]http://milesj.me[/url].');
$code->defaults();
// Or load filters and hooks
echo $code->parse();
```

And that's it! In the following sections, you will learn about the powerful Filter, Hook and Engine system.

## Configuration ##

To configure the Decoda instance, pass an array of settings as the second constructor argument, or call the individual methods below.

```php
$code = new Decoda($string, array(
    'xhtmlOutput' => true,
    'strictMode' => false,
    'escapeHtml' => true
));
```

### Using custom configuration paths ###

If you would like to use your own custom configuration for censored, emoticons and messages, you can define new lookup paths using `addPath()`.

```php
$code->addPath(__DIR__ . '/custom/config/path/');
```

### Changing the Brackets ###

By default, all tags are wrapped in square brackets. Use `setBrackets()` to change them.

```php
$code->setBrackets('{', '}');
```

### Changing the Translations ###

Decoda comes with a built-in translation dictionary, which is used to translate words like "mail" and "quote". To see the list of supported locales, open up the `src/Decoda/config/messages.json` file, and then use `setLocale()` to change it.

```php
$code->setLocale('de-de');
```

### Shorthand Links ###

If you would like a hyperlink to display its shorthand variant (displaying the word link or mail, instead of the text/url/email), you would call the method `setShorthand()`.

```php
$code->setShorthand(true);
```

### Using XHTML ###

By default all strings are parsed as HTML, if you would like to use XHTML, call `setXhtml()`.

```php
$code->setXhtml(true);
```

### Strict Mode ###

When strict mode is enabled, all tag attributes will be required to use double quotes. When disabled, attributes with and without wrapping double quotes will be parsed.

```php
$code->setStrict(true);
```

### Newline Handling ###

To set the max amount of newlines, call `setMaxNewlines()`.

```php
$code->setMaxNewlines(3);
```

Or to toggle newline to line break (nl2br) conversion, call `setLineBreaks()`.

```php
$code->setLineBreaks(false);
```

### Whitelisting Tags ###

To only parse specific tags, pass an array of whitelisted tags.

```php
$code->whitelist('b', 'i', 'u');
```

### Blacklisting Tags ###

To not parse specific tags, pass an array of blacklisted tags.

```php
$code->blacklist('b', 'i', 'u');
```

### Disable Parsing ###

To disable parsing all together, use `disable()`.

```php
$code->disable(true);
```

## Using Filters ##

Filters are a very powerful and flexible system that adds support for tag analysis and parsing. Filters are a packaging of similar tags into a single class, which contains a mapping of rules for how each tag behaves. You can view all the available filters in the `src/Decoda/Filter/` folder. By default no filters are installed, to add them use `Decoda::addFilter()`.

```php
$code = new Decoda\Decoda($string);
$code->addFilter(new Decoda\Filter\EmailFilter());
$code->addFilter(new Decoda\Filter\UrlFilter());
```

To remove certain filters from being applied, for example, one doesn't want to parse quotes, one can call `removeFilter()`. The argument should be the name of the filter class without "Filter".

```php
$code->removeFilter('Quote');
```

One can also reset and remove all filters by calling `resetFilters()`.

```php
$code->resetFilters();
```

To enable all filters, use `defaults()`.

```php
$code->defaults();
```

A custom key can be used by passing a string as the second argument.

```php
$code->addFilter(new Decoda\Filter\UrlFilter(), 'foo');
```

## Using Hooks ##

Hooks are a versatile system that allow you to hook into the parsing cycle to inject and alter the output. Hooks currently support `beforeParse()`, `afterParse()`, `beforeStrip()` and `afterStrip()`. You can view all the available hooks in the `src/Decoda/Hook/` folder. By default no hooks are installed, to add them use `Decoda::addHook()`.

```php
$code = new Decoda\Decoda($string);
$code->addHook(new Decoda\Hook\EmoticonHook());
```

To remove certain hooks, use `removeHook()`. The argument should be the name of the class without "Hook".

```php
$code->removeHook('Censor');
```

One can also reset and remove all hooks by calling `resetHooks()`.

```php
$code->resetHooks();
```

To enable all hooks, use `defaults()`.

```php
$code->defaults();
```

A custom key can be used by passing a string as the second argument.

```php
$code->addHook(new Decoda\Hook\CensorHook(), 'foo');
```

## Using Engines ##

By default all templates are rendered using regular PHP code. To use a custom template rendering engine (for example, Twig), one can create a new engine class and pass it to Decoda.

```php
$code = new Decoda\Decoda($string);
$code->setEngine(new Decoda\Engine\TwigEngine())
```

To use custom templates outside of Decoda, one can set the template path within the engine.

```php
$engine = new Decoda\Engine\PhpEngine();
$engine->addPath('/path/to/custom/templates/');

$code->setEngine($engine);
```

For more information on how to create engines, skip to the Creating Engines chapter.

## Using Loaders ##

A loader is a handler that will load data from any kind of resource. The primary method of loading data is reading files using the FileLoader, or passing static data using the DataLoader. The loader system exists so that data can be loaded from a database, or an API, or flat files, or basically anywhere.

The FileLoader will detect the current file type and parse the contents out of PHP, INI, JSON, and text files; other types will throw an exception. The DataLoader accepts an array as it's constructor argument and will in turn use that as the data.

For example, adding custom locale message strings.

```php
$code->addMessages(new Decoda\Loader\DataLoader(array('spoiler' => 'Spoiler'));
$code->addMessages(new Decoda\Loader\FileLoader('/path/to/messages.php'));
```

Or adding custom data to hooks.

```php
$censor = new Decoda\Hook\CensorHook();
$censor->addLoader(new Custom\DatabaseLoader());
```

## Creating Filters ##

To add a new filter, create a new filter class and name it accordingly, for example AudioFilter. The class will need a property called `$_tags` that will contain a mapping of all tags and their rules. The following rules are available.

* `tag` (string) - Decoda tag
* `htmlTag` (string) - HTML replacement tag
* `template` (string) - Template file to use for rendering
* `displayType` (constant) - Type of HTML element: block or inline
* `allowedTypes` (constant) - What types of elements are allowed to be nested
* `attributes` (array) - Custom attributes to parse out of the Decoda markup
* `mapAttributes` (array) - Map parsed and custom attributes to different names
* `htmlAttributes` (array) - Custom HTML attributes to append to the parsed tag
* `escapeAttributes` (bool) - Escape HTML entities within the parsed attributes
* `lineBreaks` (bool) - Convert linebreaks within the content body
* `autoClose` (bool) - HTML tag is self closing
* `preserveTags` (bool) - Will not convert nested Decoda markup within this tag
* `onlyTags` (bool) - Allow only tag nodes and no text nodes as direct descendants
* `contentPattern` (regex) - Regex pattern that the content or default attribute must pass
* `parent` (array) - List of Decoda tags that this tag can only be a direct child of
* `childrenWhitelist` (array) - List of Decoda tags that can only be a direct descendant
* `childrenBlacklist` (array) - List of Decoda tags that can not be a direct descendant
* `maxChildDepth` (integer) - Max depth for nested children of the same tag (-1 to disable)
* `persistContent` (bool) - Should we persist text content from within deeply nested tags (but remove their wrapping tags)

Of all the rules, only the following are required: `htmlTag`, `displayType`, and `allowedTypes`. For displayType and allowedTypes -- block elements can have nested inline and block elements, while inline can only nest other inlines. Let's now add our audio example:

```php
namespace Decoda\Filter;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;

class AudioFilter extends AbstractFilter {
    protected $_tags = array(
        'audio' => array(
            'htmlTag' => 'audio',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_NONE,
            'template' => 'audio',
            'contentPattern' => '/^(.*?)\.(ogg|mp3)$/is',
            'attributes' => array(
                'default' => '/^(ogg|mp3)$/i',
                'autoplay' => '/^(true|false)$/i',
                'controls' => '/^(true|false)$/i'
            ),
            'mapAttributes' => array(
                'default' => 'type'
            )
        )
    );
}
```

The defined rules basically state that we want to parse any [audio] tags, that have a default attribute value of ogg/mp3, can have an autoplay attribute, must have the content end in ogg/mp3, may not have any other nested tags and will use "audio" as a template. The rules would match the following:

```
[audio="mp3"]song.mp3[/audio]
[audio="ogg"]song.ogg[/audio]
[audio="ogg" autoplay="true"]song.ogg[/audio]
```

This would then render using the `src/Decoda/templates/audio.php` template. All parsed out `$attributes` are available as variables in the template. The `$type` variable is actually the remapped default attribute, which was defined in the audio rules.

```php
<audio autoplay="<?php echo (isset($autoplay) &amp;&amp; $autoplay == 'true') ? 'autoplay' : ''; ?>" controls="<?php echo (isset($controls) &amp;&amp; $controls == 'true') ? 'controls' : ''; ?>">
    <source src="<?php echo $content; ?>" type="audio/<?php echo $type; ?>" />
</audio>
```

And that's pretty much how the filter system works. To get a more advanced understand of how certain rules work, or the best scenario to use them, take a look at all the current filters.

## Creating Hooks ##

To add a new hook, create a new class and name it accordingly, for example GrammarHook. The class takes five optional methods, `startup()`, `beforeParse()`, `afterParse()`, `beforeStrip()` and `afterStrip()`. All methods (excluding startup) take the parsed string as their first argument, which in turn needs to be returned back to the parser.

```php
namespace Decoda\Hook;

use Decoda\Hook\AbstractHook;

class GrammarHook extends AbstractHook {
    public function startup() { }
    public function beforeParse($content) { }
    public function afterParse($content) { }
    public function beforeStrip($content) { }
    public function afterStrip($content) { }
}
```

The `startup()` method is triggered before any callback is triggered, but long after the constructor has initialized. This allows the hook to setup any data and parse any necessary Loaders.

That's all it takes to create your own hooks. For more advanced examples, take a look at the current hooks.

## Creating Engines ##

To render different template types besides PHP, one can create an engine. The engine class must implement all the methods in the Engine interface (or extend from AbstractEngine) as well as implementing the `render()` method. The `render()` method receives two arguments: the first is the tag array parsed from filters, while the second is the actual inner content (which can usually be ignored).

```php
namespace Decoda\Engine;

use Decoda\Engine\AbstractEngine;

class TwigEngine extends AbstractEngine {
    public function render(array $tag, $content) {
        $setup = $this->getFilter()->getTag($tag['tag']);

        $loader = new Twig_Loader_Filesystem($this->getPaths());
        $twig = new Twig_Environment($loader);

        return $twig->render($setup['template'] . '.html', $tag['attributes']); 
    }
}
```

And now we have custom template parsing using the Twig engine. For more advanced examples, take a look at the current engines.

## Creating Loaders ##

To add a new loader, create a new class and name it accordingly, for example DatabaseLoader. The loader must implement the Loader interface or the AbstractLoader class and define the `load()` method. Furthermore, since the AbstractLoader extends the Component system, a loader can accept an array of configuration via the constructor.

```php
namespace Decoda\Loader;
 
use Decoda\Loader\AbstractLoader;
 
class DatabaseLoader extends AbstractLoader {
    public function load() {
        // query the database and return results
    }
}
```

For example, we can pass database login information through the constructor (there should be more security of course, but this is just a simple example).

```php
$loader = new DatabaseLoader(array(
    'user' => 'root',
    'pass' => 'foobar',
    'name' => 'database',
    'host' => 'localhost'
));
```

That's all it takes to create loaders. For more advanced examples, take a look at the current loaders.

## Validating Input ##

Decoda comes built in with a system to detect incorrectly nested tags, broken tags or incorrectly nested scope types. After `parse()` has been executed, use the `getErrors()` method to grab all the errors for the last parse cycle. The method accepts a single argument which filters what type of errors should be returned.

```php
$code->getErrors(); // all 3 errors
$code->getErrors(Decoda::ERROR_NESTING); // incorrect nesting order
$code->getErrors(Decoda::ERROR_CLOSING); // no closing tags
$code->getErrors(Decoda::ERROR_SCOPE); // tags nested within invalid types
```

The method will return an array of tags that have failed, so that you can output some kind of error message to the user and block the data being saved to the database. The array changes per error type, so be sure to loop over each correctly. Something like the following should suffice:

```php
$nesting = array(); 
$closing = array();
$scope = array();

foreach ($code->getErrors() as $error) {
    switch ($error['type']) {
        case Decoda::ERROR_NESTING:    $nesting[] = $error['tag']; break;
        case Decoda::ERROR_CLOSING:    $closing[] = $error['tag']; break;
        case Decoda::ERROR_SCOPE:    $scope[] = $error['child'] . ' in ' . $error['parent']; break;
    }
}

if (!empty($nesting)) {
    $errors[] = sprintf('The following tags have been nested in the wrong order: %s', implode(', ', $nesting));
}

if (!empty($closing)) {
    $errors[] = sprintf('The following tags have no closing tag: %s', implode(', ', $closing));
}

if (!empty($scope)) {
    $errors[] = sprintf('The following tags can not be placed within a specific tag: %s', implode(', ', $scope));
}
```

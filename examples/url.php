<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\UrlFilter());
$code->addHook(new \Decoda\Hook\ClickableHook()); ?>

<h2>URL</h2>

<?php $string = 'Valid websites:
[url]http://domain.com[/url]
[url]https://securesite.com[/url]
[url]ftp://ftpsite.com[/url]
[url]irc://ircsite.com[/url]
[url]telnet://telnetsite.com[/url]
[url="http://domain.com"]Linked URL[/url]

Valid websites (auto-linked with hook):
http://domain.com
http://sub.domain.com/?with=param
http://user:pass@domain.com:80/?with=param

Invalid websites:
[url]domain.com[/url]
[url]www.domain.com[/url]
[url]wtf://unsupportedprotocol.com/[/url]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Link</h2>

<?php $string = 'Valid websites:
[link]http://domain.com[/link]
[link]https://securesite.com[/link]
[link]ftp://ftpsite.com[/link]
[link]irc://ircsite.com[/link]
[link]telnet://telnetsite.com[/link]
[link="http://domain.com"]Linked URL[/link]

Valid websites (auto-linked with hook):
http://domain.com
http://sub.domain.com/?with=param
http://user:pass@domain.com:80/?with=param

Invalid websites:
[link]domain.com[/link]
[link]www.domain.com[/link]
[link]wtf://unsupportedprotocol.com/[/link]';

$code->reset($string);
echo $code->parse(); ?>

<h2>URL <span>with shorthand</span></h2>

<?php $string = '[url]http://domain.com[/url]
[url]https://securesite.com[/url]
[url]ftp://ftpsite.com[/url]
[url]irc://ircsite.com[/url]
[url]telnet://telnetsite.com[/url]';

$code->setShorthand();
$code->reset($string);
echo $code->parse(); ?>
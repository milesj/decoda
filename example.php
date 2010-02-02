<?php

// Turn on error reporting
error_reporting(E_ALL);

// Include Decoda
include('decoda.php');

// Fake string
$string = '[div id="first"][b]Lorem ipsum dolor sit amet[/b], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/div]
[div class="second again"][i]Lorem ipsum dolor sit amet[/i], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/div]
[quote][u]Lorem ipsum dolor sit amet[/u], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor. Curabitur sed tellus. Donec id dolor.[/quote]
[quote="Miles" date="02/26/1988 12:34:21"][url=http://www.milesj.me]Lorem ipsum dolor sit amet[/url], consectetuer adipiscing elit. Aliquam laoreet pulvinar sem. Aenean at odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec elit. Fusce eget enim. Nullam tellus felis, sodales nec, sodales ac, commodo eu, ante. Curabitur tincidunt, lacus eget iaculis tincidunt, elit libero iaculis arcu, eleifend condimentum sem est quis dolor.[/quote]

[code lang="php" hl="15"]/**
 * Parse the default markup depending on the allowed
 * @param string $string
 * @return string
 */
protected function parseDefaults($string) {
	if (empty($this->allowed)) {
		$code = $this->markupCode;
		$result = $this->markupResult;
	} else {
		$code = array();
		$result = array();
		foreach ($this->markupCode as $tag => $regex) {
			if (in_array($tag, $this->allowed)) {
				$code[$tag] = $this->markupCode[$tag];
				$result[$tag] = $this->markupResult[$tag];
			}
		}
	}

	$string = preg_replace($code, $result, $string);
	return $string;
}[/code]';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Decoda Example</title>
</head>
<body>

<?php // Decode and parse
$code = new Decoda($string);
$code->useShorthand(false);
$code->makeClickable(true);
$code->parse(); ?>

</body>
</html>

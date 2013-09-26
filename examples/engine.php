<?php

class TestEngine extends \Decoda\Engine\AbstractEngine {

    public function render(array $tag, $content) {
        $setup = $this->getFilter()->getTag($tag['tag']);
        $paths = $this->getPaths();
        $path = $paths[0] . $setup['template'] . '.tpl';

        if (!file_exists($path)) {
            throw new Exception(sprintf('Template file %s does not exist', $setup['template']));
        }

        $vars = array();

        foreach ($tag['attributes'] as $key => $value) {
            if (isset($setup['map'][$key])) {
                $key = $setup['map'][$key];
            }

            $vars[$key] = $value;
        }

        extract($vars, EXTR_SKIP);
        ob_start();

        include $path;

        return ob_get_clean();
    }

}

// Lets change the template path and the file extension.
$engine = new TestEngine();
$engine->addPath(__DIR__ . '/templates/');

$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\QuoteFilter());
$code->setEngine($engine); ?>

<h2>Test Rendering</h2>

<p>Uses a different template engine, with a separate templates folder and a different file extension.</p>

<br>

<?php $string = '[quote]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
    [quote="Miles"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]
    [quote date="1313728971"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
        [quote="Miles" date="2011-02-26 06:42:33"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
            [quote]This 3rd level quote will not be rendered.[/quote][/quote][/quote]
    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
[/quote]';

$code->reset($string);
echo $code->parse(); ?>
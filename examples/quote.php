<?php
$code = new \Decoda\Decoda();
$code->addFilter(new \Decoda\Filter\QuoteFilter()); ?>

<h2>Quote</h2>

<?php $string = '[quote]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Quote <span>with author</span></h2>

<?php $string = '[quote="Miles"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Quote <span>with date</span></h2>

<?php $string = '[quote date="1313728971"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]
[quote date="2011-02-26 06:42:33"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Quote <span>with author and date</span></h2>

<?php $string = '[quote="Miles Johnson" date="2011-02-26 06:42:33"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]';

$code->reset($string);
echo $code->parse(); ?>

<h2>Nested quotes</h2>

<?php $string = '[quote]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
    [quote="Miles"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.[/quote]
    [quote date="1313728971"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
        [quote="Miles" date="2011-02-26 06:42:33"]Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque non sapien a eros tincidunt accumsan. Ut nisl dui, dignissim at posuere quis, facilisis eget lectus. Morbi vitae massa eu metus pharetra rhoncus. Suspendisse potenti. Phasellus laoreet dapibus dapibus. Duis faucibus lacinia diam, nec pharetra est pharetra vitae. Etiam sodales, nulla et ullamcorper mattis, augue nunc sollicitudin risus, nec imperdiet est leo vitae est. Integer ultricies, metus at scelerisque interdum, sapien lorem mollis orci, vel mattis felis augue vitae nunc. Fusce eget sem sed orci interdum commodo sit amet et metus. In ultricies feugiat eleifend. Aliquam erat volutpat.
            [quote]This 3rd level quote will not be rendered.[/quote][/quote][/quote]
    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
[/quote]';

$code->reset($string);
echo $code->parse(); ?>
<?php
$filter = $this->getFilter();
$show = $filter->message('spoiler') . ' (' . $filter->message('show') . ')';
$hide = $filter->message('spoiler') . ' (' . $filter->message('hide') . ')';

$counter = rand();
$click  = "document.getElementById('spoiler-content-". $counter ."').style.display = (document.getElementById('spoiler-content-". $counter ."').style.display == 'block' ? 'none' : 'block'); ";
$click .= "this.innerHTML = (document.getElementById('spoiler-content-". $counter ."').style.display == 'block' ? '". $hide ."' : '". $show ."');"; ?>

<div class="decoda-spoiler">
	<button class="decoda-spoiler-button" type="button" onclick="<?php echo $click; ?>"><?php echo $show; ?></button>

	<div class="decoda-spoiler-content" id="spoiler-content-<?php echo $counter; ?>" style="display: none">
		<?php echo $content; ?>
	</div>
</div>
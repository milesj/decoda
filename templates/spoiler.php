<?php

$show = Decoda::message('spoiler') .' ('. Decoda::message('show') .')';
$hide = Decoda::message('spoiler') .' ('. Decoda::message('hide') .')';
$counter = rand();

$click  = "document.getElementById('spoilerContent-". $counter ."').style.display = (document.getElementById('spoilerContent-". $counter ."').style.display == 'block' ? 'none' : 'block');";
$click .= "this.innerHTML = (document.getElementById('spoilerContent-". $counter ."').style.display == 'block' ? '". $hide ."' : '". $show ."');"; ?>		
			
<div class="decoda-spoiler">
	<button class="spoiler-button" type="button" onclick="<?php echo $click; ?>"><?php echo $show; ?></button>
	
	<div class="spoiler-content" id="spoilerContent-<?php echo $counter; ?>" style="display: none">
		<?php echo $content; ?>
	</div>
</div>
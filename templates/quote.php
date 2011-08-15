<?php
if (!empty($date)) {
	$date = date('M jS Y, H:i:s', is_numeric($date) ? $date : strtotime($date));
} ?>

<blockquote class="decoda-quote">
	<?php if (!empty($author)) { ?>
		<div class="decoda-quoteAuthor">
			<?php if (!empty($date)) { ?>
				<div class="decoda-quoteDate">
					<?php echo $date; ?>
				</div>
			<?php } ?>
			
			<?php echo $this->message('quoteBy', array(
				'author' => htmlentities($author, ENT_NOQUOTES, 'UTF-8')
			)); ?>
		</div>
	<?php } else if (!empty($date)) { ?>
		<div class="decoda-quoteDate">
			<?php echo $date; ?>
		</div>
	<?php } ?>
	
	<div class="decoda-quoteBody">
		<?php echo $content; ?>
	</div>
</blockquote>

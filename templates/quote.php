
<blockquote class="decoda-quote">
	<?php if (!empty($author)) { ?>
		<div class="decoda-quoteAuthor">
			<?php if (!empty($date)) { ?>
				<span class="decoda-quoteDate">
					<?php echo date('M jS Y, H:i:s', is_numeric($date) ? $date : strtotime($date)); ?>
				</span>
			<?php } ?>
			
			<?php echo $this->message('quoteBy', array('author' => $author)); ?>
		</div>
	<?php } ?>
	
	<div class="decoda-quoteBody">
		<?php echo $content; ?>
	</div>
</blockquote>
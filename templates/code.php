<?php // Place $content directly within the tags to not leave any whitespace for <pre> ?>

<pre class="decoda-code <?php if (!empty($lang)) { echo 'code-'. $lang; } ?>" data-highlight="<?php echo isset($hl) ? $hl : ''; ?>"><?php echo $content; ?></pre>

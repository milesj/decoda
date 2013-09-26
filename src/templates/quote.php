<blockquote class="decoda-quote">
    <?php if (!empty($author) || !empty($date)) { ?>
        <div class="decoda-quote-head">
            <?php if (!empty($date)) { ?>
                <span class="decoda-quote-date">
                    <?php echo date($dateFormat, is_numeric($date) ? $date : strtotime($date)); ?>
                </span>
            <?php }

            if (!empty($author)) { ?>
                <span class="decoda-quote-author">
                    <?php echo $this->getFilter()->message('quoteBy', array(
                        'author' => htmlentities($author, ENT_NOQUOTES, 'UTF-8')
                    )); ?>
                </span>
            <?php } ?>

            <span class="clear"></span>
        </div>
    <?php } ?>

    <div class="decoda-quote-body">
        <?php echo $content; ?>
    </div>
</blockquote>
<?php
function banner(
  string $link,
  string $title,
  string $copy,
  string $button_copy,
  ?string $extra_class = null,
  ?string $headline_class = null,
  ?string $button_class = null
) { ?>
  <div class="banner gap--20 <?php echo strlen($extra_class) > 0 ? ' ' . $extra_class : ''; ?>">
    <div class="inner">
      <div class="title">
        <h1 class="<?php echo strlen($headline_class) > 0 ? $headline_class : ''; ?>"><?php _e($title, 'momgabay'); ?></h1>
      </div>
      <?php if (strlen($copy) > 0) { ?>
        <div class="copy">
          <?php _e($copy, 'mongabay'); ?>
        </div>
      <?php } ?>
      <a href="<?php echo $link; ?>" class="theme--button primary <?php echo strlen($button_class) > 0 ? $button_class : ''; ?>">
        <?php _e($button_copy, 'mongabay'); ?><span class="icon icon-right"></span>
      </a>
    </div>
  </div>
<?php }
?>
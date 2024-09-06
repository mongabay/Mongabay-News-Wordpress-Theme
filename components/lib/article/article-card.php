<?php
function article_card()
{ ?>
  <div class="article--container pv--8">
    <a href="<?php the_permalink(); ?>">
      <?php if (has_post_thumbnail()) { ?>
        <div class="featured-image">
          <?php echo get_icon(get_the_ID()); ?>
          <?php the_post_thumbnail('medium') ?>
        </div>
      <?php }; ?>
      <div class="title headline">
        <h3><?php the_title(); ?></h3>
      </div>
      <div class="post-meta pv--8">
        <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
        <span class="date"><?php the_time('j M Y'); ?></span>
      </div>
    </a>
  </div>
<?php }
?>
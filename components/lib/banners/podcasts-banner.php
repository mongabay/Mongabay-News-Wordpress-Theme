<?php
function podcasts_banner($has_podcasts = true)
{
  $args = array(
    'post_type' => 'podcasts',
    'posts_per_page' => 1,
    'cache_results' => true,
  );
  $query = new WP_Query($args);

  if ($query->have_posts() && $has_podcasts) {
    while ($query->have_posts()) {
      $query->the_post(); ?>
      <div class="container in-column gap--40">
        <h1><?php _e('Listen to Nature with thought-provoking <span class="icon icon-podcast">podcasts</span>', 'mongabay'); ?></h1>
        <div class="in-column gap--20">

          <div class="full-width sound-wave">
            <div class="article--container narrow">
              <?php if (has_post_thumbnail()) { ?>
                <div class="featured-image">
                  <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                </div>
              <?php }; ?>
            </div>
          </div>
          <div class="article--container">
            <div class="title headline gap--8">
              <h3 class="text-center">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h3>
              <div class="post-meta text-center">
                <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                <span class="date"><?php the_time('j M Y'); ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php }
  } else {
    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'post_offset' => 6,
      'posts_per_page' => 8,
      'cache_results' => true,
    );
    // articles_listing_in_columns(array('post'), 6, 6, 'medium', null, false);

    $posts_query = new WP_Query($args);

    if ($posts_query->have_posts()) { ?>
      <div class="container grid--4 gap--20">
        <?php
        while ($posts_query->have_posts()) {
          $posts_query->the_post();
        ?>
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
        echo '</div>';
      }
    }
  }
  ?>
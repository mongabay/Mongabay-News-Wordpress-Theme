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
            <div class="title headline gap--8 align-center">
              <h3 class="text-center podcast--banner-title">
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
      'offset' => get_enabled_features('specials') ? 6 : 14,
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
        article_card();
      }
      echo '</div>';
    }
  }
}
  ?>
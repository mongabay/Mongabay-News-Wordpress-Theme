<?php
function articles_listing_in_columns(
  array $post_formats,
  int $posts_per_page,
  int $offset,
  string $thumbnail_size,
  ?string $taxonomy,
  ?bool $is_featured
) {
  $args = array(
    'post_type' => $post_formats,
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
    'cache_results' => true,
  );

  if ($taxonomy) {
    $args['taxonomy'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );
  }

  if ($is_featured) {
    $args['meta_query'] = array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    );
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    echo '<div class="container in-column gap--40">';
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;

      if ($post_counter === 1) {
        //open first row
        echo '<div class="container in-row gap--40 primary">';
      }

      if ($post_counter === 3) {
        echo '<div class="container grid--4 gap--40 secondary">';
      }
?>

      <div class="article--container pv--8">
        <a href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php echo get_icon(get_the_ID()); ?>
              <?php the_post_thumbnail($thumbnail_size) ?>
            </div>
          <?php }; ?>
          <div class="title headline left">
            <?php if ($post_counter < 3 && !wp_is_mobile()) { ?>
              <h1><?php the_title(); ?></h1>
            <?php } else { ?>
              <h3><?php the_title(); ?></h3>
            <?php }; ?>
          </div>
          <div class="post-meta pv--8">
            <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
            <span class="date"><?php the_time('j M Y'); ?></span>
          </div>
        </a>
      </div>

      <?php
      if ($post_counter === 2) {
        // close first row
        echo '</div>';
      }
      if ($post_counter === 6) {
        // close second row
        echo '</div>';
      }
      ?>
<?php }
    echo '</div>';
    wp_reset_postdata();
  }
}
?>
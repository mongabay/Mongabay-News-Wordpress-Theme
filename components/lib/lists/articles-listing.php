<?php
function articles_listing(
  array $post_formats,
  int $posts_per_page,
  int $offset,
  bool $show_all_thumbnails,
  string $thumbnail_size,
  ?string $odd_item,
  ?int $odd_item_position,
  ?string $taxonomy
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

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;

      $isFeatured = get_post_meta(get_the_ID(), 'featured_as', true) === 'featured';
?>
      <?php if (strlen($odd_item) > 0 && $post_counter == $odd_item_position) {
        echo $odd_item;
      } ?>
      <div class="article--container">
        <a href="<?php the_permalink(); ?>">
          <?php if (($show_all_thumbnails || $post_counter == 1) && has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php echo get_icon(get_the_ID()); ?>
              <?php the_post_thumbnail($thumbnail_size) ?>
            </div>
          <?php }; ?>
          <?php if ($isFeatured) { ?>
            <div class="featured-label"><?php _e('Feature story', 'mongabay'); ?></div>
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
    wp_reset_postdata();
  }
}
?>
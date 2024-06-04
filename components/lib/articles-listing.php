<?php
function articles_listing(
  string $post_format,
  int $posts_per_page,
  int $offset,
  bool $show_all_thumbnails,
  string $thumbnail_size,
  ?string $taxonomy
) {
  $args = array(
    'post_type' => $post_format,
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
?>
      <div class="article--container pv--8">
        <a href="<?php the_permalink(); ?>">
          <?php if (($show_all_thumbnails || $post_counter == 1) && has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php the_post_thumbnail($thumbnail_size) ?>
            </div>
          <?php }; ?>
          <div class="title headline">
            <h3><?php the_title(); ?></h3>
          </div>
          <div class="meta pv--8">
            <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
            <span class="date"><?php the_time('j F Y'); ?></span>
          </div>
        </a>
      </div>
<?php }
    wp_reset_postdata();
  }
}
?>
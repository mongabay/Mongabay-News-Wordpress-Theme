<?php
function articles_listing_condensed(
  string $post_type,
  int $posts_per_page,
  int $offset,
  bool $show_featured,
  string $thumbnail_size,
  ?string $taxonomy
) {
  $args = array(
    'post_type' => $post_type,
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
  $counter = 0;
  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $counter++;
      $query->the_post();
?>
      <?php if ($post_type === 'short-article') {
        shorts_article_card(get_the_ID(), false, '', $counter, true);
      } else { ?>
        <div class="article--container gap--16 rounded">
          <a href="<?php the_permalink(); ?>">
            <?php if ($show_featured && has_post_thumbnail() && $counter === 1) { ?>
              <div class="featured-image">
                <?php the_post_thumbnail($thumbnail_size) ?>
              </div>
            <?php } else { ?>
              <div class="title headline bg-theme-gray ph--40 pv--40">
                <h3><?php the_title(); ?></h3>
              </div>
              <div class="featured-image">
                <?php the_post_thumbnail($thumbnail_size) ?>
              </div>
            <?php }; ?>
          </a>
        </div>
      <?php } ?>

<?php }
    wp_reset_postdata();

    if ($post_type === 'short-article') {
      get_shorts_dialog();
    }
  } else {
    esc_html_e('Sorry, no posts matched your criteria.');
  }
}
?>
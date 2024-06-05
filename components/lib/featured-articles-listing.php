<?php
function featured_articles_listing(
  string $post_type,
  int $posts_per_page,
  int $offset,
  string $thumbnail_size,
  int $items_in_row,
  ?bool $show_featured,
  ?string $taxonomy
) {
  $args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => $post_type,
    'post_status' => 'publish',
    'offset' => $offset,
    'cache_results' => true,
  );

  if ($show_featured == true) {
    $args['meta_query'] = array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    );
  }

  if ($taxonomy) {
    $$args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    echo '<div class="article--container full-width">';

    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
?>

      <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image">
              <?php the_post_thumbnail($thumbnail_size) ?>
              <div class="title headline <?php echo $post_type !== 'post' ? 'text-center' : ''; ?>">
                <h1><?php the_title(); ?></h1>
                <?php if ($post_type !== 'post') { ?>
                  <div class="meta">
                    <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                    <span class="date"><?php the_time('j F Y'); ?></span>
                  </div>
                <?php } ?>
              </div>
            </div>
          </a>
        </div>
      <?php } else {
        if ($post_counter == 2) {
          echo '<div class="grid--' . ($items_in_row) . ' gap--40 pv--40">';
        }; ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) { ?>
              <div class="featured-image">
                <?php the_post_thumbnail($thumbnail_size) ?>
              </div>
            <?php }; ?>
            <div class="title headline pv--16">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="meta">
              <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
              <span class="date"><?php the_time('j F Y'); ?></span>
            </div>
          </a>
        </div>
      <?php }; ?>
<?php }
    echo '</div></div>';
    wp_reset_postdata();
  } else {
    esc_html_e('Sorry, no posts matched your criteria.');
  }
}
?>
<?php
function featured_articles_listing(
  array $post_types,
  int $posts_per_page,
  int $offset,
  string $thumbnail_size,
  int $items_in_row,
  ?bool $show_featured = false,
  ?string $taxonomy = null,
  ?string $taxonomy_term = null
) {
  $args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => $post_types,
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
    $args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
        'terms' => $taxonomy_term,
      )
    );
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  $is_posts_only = count($post_types) === 1 && $post_types[0] === 'post';

  if ($query->have_posts()) {
    echo '<div class="article--container full-width">';

    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
?>

      <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
        <div class="article--container full-width">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image">
              <?php echo get_icon(get_the_ID()); ?>
              <?php the_post_thumbnail($thumbnail_size) ?>
              <div class="img-overlay"></div>
              <?php if (!$taxonomy) { ?>
                <div class="title headline <?php echo $is_posts_only ? 'text-center' : ''; ?>">
                  <h1><?php the_title(); ?></h1>
                  <?php if ($is_posts_only) { ?>
                    <div class="meta">
                      <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                      <span class="date"><?php the_time('j F Y'); ?></span>
                    </div>
                  <?php } ?>
                </div>
              <?php } ?>
            </div>
            <?php if ($taxonomy) { ?>
              <div class="title headline <?php echo $post_types !== 'post' ? 'text-center' : ''; ?>">
                <h1><?php the_title(); ?></h1>
                <div class="meta">
                  <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                  <span class="date"><?php the_time('j F Y'); ?></span>
                </div>
              </div>
            <?php } ?>
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
                <?php echo get_icon(get_the_ID()); ?>
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
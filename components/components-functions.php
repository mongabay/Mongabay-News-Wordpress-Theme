<?php
function articles_listing($posts_per_page, $offset, $show_all_thumbnails, $thumbnail_size, $taxonomy)
{
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
  );

  if ($taxonomy) {
    $tax_query = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );

    $args['taxonomy'] = $tax_query;
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
?>
      <div class="article--container ph--8">
        <a href="<?php the_permalink(); ?>">
          <?php if (($show_all_thumbnails || $post_counter == 1) && has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php echo get_the_post_thumbnail(get_the_ID(), $thumbnail_size) ?>
            </div>
          <?php }; ?>
          <div class="title headline">
            <h3><?php the_title(); ?></h3>
          </div>
          <div class="meta">
            <span class="byline"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'byline', '', ', ', '')); ?></span>
            <span class="date"><?php the_time('j F Y'); ?></span>
          </div>
        </a>
      </div>
    <?php }
    wp_reset_postdata();
  } else {
    esc_html_e('Sorry, no posts matched your criteria.');
  }
}

function featured_articles_listing($posts_per_page, $offset, $thumbnail_size, $taxonomy)
{
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
  );

  if ($taxonomy) {
    $tax_query = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );

    $args['taxonomy'] = $tax_query;
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    echo '<div class="article--container ph--8">';

    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
    ?>

      <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image">
              <?php echo get_the_post_thumbnail(get_the_ID(), 'medium') ?>
              <div class="title headline">
                <h1><?php the_title(); ?></h1>
              </div>
            </div>
          </a>
        </div>
      <?php } else {
        if ($post_counter == 2) {
          echo '<div class="grid--2 gap--40">';
        }; ?>
        <div class="article--container ph--8">
          <a href="<?php the_permalink(); ?>">
            <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
              <div class="featured-image">
                <?php echo get_the_post_thumbnail(get_the_ID(), $thumbnail_size) ?>
              </div>
            <?php }; ?>
            <div class="title headline">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="meta">
              <span class="byline"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'byline', '', ', ', '')); ?></span>
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

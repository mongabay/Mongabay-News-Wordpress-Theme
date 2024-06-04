<?php
function videos_latest($offset)
{
  $args = array(
    'post_type' => 'videos',
    'posts_per_page' => 5,
    'cache_results' => true,
    'offset' => $offset,
  );

  $counter = 0;
  $query = new WP_Query($args);

  if ($query->have_posts()) {
    echo '<div class="container ph--40 pv--40">';
    while ($query->have_posts()) {
      $query->the_post();
      $counter++;
?>

<?php }
    echo '</div>';
    wp_reset_postdata();
  }
}
?>
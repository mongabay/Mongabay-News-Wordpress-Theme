<?php

/**
 * Template Name: Shorts Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>

<?php
$args = array(
  'post_type' => 'short-article',
  'posts_per_page' => 8,
  'offset' => 0,
  'post_status' => 'publish',
);

$query = new WP_Query($args);
$post_counter = 0;

$banner = '
<div class="banner gap--20 ph--20 pv--20 outlined">
<div class="inner">
  <div class="title">
    <h1 class="lh--tight">Subscribe</h1>
  </div>
  <div class="copy">
    Stay informed with news and inspiration from nature’s frontline.
  </div>
  <a href="" class="theme--button primary full-width">
    Newsletter<span class="icon icon-right"></span>
  </a>
</div>
</div>';

?>
<div class="container ph--40 pv--40 in-column">

  <?php if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;

      shorts_grid($post_counter, $banner);
    }
    get_shorts_dialog(true);
  } else {
    _e('No short articles found', 'mongabay');
  }
  ?>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
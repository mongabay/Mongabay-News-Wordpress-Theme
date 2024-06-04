<?php

/**
 * Template Name: Series Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<?php
$series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'global-forests'));
formats_slider('post', 'Mongabay series gather storieswith a lot in common.', $series);
?>
<div class="container ph--40 pv--40 gap--20 full-width in-column">
  <?php
  $series_latest = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'indigenous-peoples-and-conservation', 'amazon-conservation'));
  series_latest($series_latest);
  ?>
  <div class="container pv--56 full-width">
    <?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
  </div>
</div>

<?php // featured_articles_slider(4, 5); 
?>

<div class="container ph--40 pv--40 full-width">
  <?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<?php // topics_section(); 
?>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
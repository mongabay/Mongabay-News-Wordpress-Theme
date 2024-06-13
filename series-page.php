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
formats_slider(array('post'), 'Mongabay series gather stories with a lot in common.', $series, 'text-center');
?>
<div class="container section--highlight ph--40 pv--40 gap--20 in-column">
  <?php
  series_articles_listing(false);

  $series_latest = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'indigenous-peoples-and-conservation', 'amazon-conservation', 'oceans', 'oceans', 'oceans'));
  series_latest($series_latest);
  ?>
  <div class="container centered">
    <a href="" class="theme--button primary"><?php _e('All specials', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>

<?php tools_slider(); ?>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
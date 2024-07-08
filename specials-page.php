<?php

/**
 * Template Name: Specials Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<?php
$specials_tax_array = (array('conservation-effectiveness', 'product-lifecycles', 'indonesia-for-sale'));
formats_slider(array('specials'), 'Special Issues connect the dots between stories', $specials_tax_array, 'text-center');
?>
<div class="container section--highlight ph--40 pv--40 gap--20 in-column">
  <?php
  series_articles_listing(false);

  $series_latest_tax_array = (array('conservation-effectiveness', 'product-lifecycles', 'indonesia-for-sale', 'conservation-potential', 'problem-solved'));
  specials_latest($series_latest_tax_array);
  ?>
  <div class="container centered">
    <a href="" class="theme--button primary"><?php _e('All Specials', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>

<?php tools_slider(); ?>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
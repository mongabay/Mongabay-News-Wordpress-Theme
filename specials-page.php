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
specials_slider('Special Issues connect the dots between stories', array(), 'text-center');
?>
<div class="container full-width in-column">
  <div class="container section--highlight ph--40 pv--40 gap--20 in-column">
    <?php
    series_articles_slider(false, 'specials');

    $series_latest_tax_array = (array('conservation-effectiveness', 'product-lifecycles', 'indonesia-for-sale', 'conservation-potential', 'problem-solved', 'congo-peatlands', 'satere-mawe', 'central-american-cattle-ranching'));
    specials_latest();
    ?>
    <div class="container centered">
      <a href="<?php home_url(); ?>/?s=&formats=specials" class="theme--button primary"><?php _e('All Specials', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>
<?php tools_slider(); ?>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
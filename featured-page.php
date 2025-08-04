<?php

/**
 * Template Name: Featured Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<?php
//$series = (array('global-forests', 'indonesian-forests', 'oceans', 'indigenous-peoples-and-conservation'));
$formats = get_default_formats();
$formats_string = get_default_search_formats($formats);
formats_slider($formats, 'In-depth feature stories reveal context and insight', array(), 'text-center');
?>

<div class="container full-width ph--40 in-column">
  <?php
  $extra_params = array(
    'link_copy' => 'all stories',
    'link_url' => home_url() . '/?s=&formats=' . $formats_string . '&featured=true',
    'featured' => true
  );

  topics_section(get_default_formats(), 'Explore enterprising journalism about', array('forests', 'environmental-crime', 'oceans', 'wildlife', 'climate'), $extra_params);
  ?>


  <div id="section-features-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="<?php echo !wp_is_mobile() ? 'align-items: center; justify-content: space-between;' : ''; ?>">
      <h1><?php _e('Latest features', 'mongabay'); ?></h1>
      <a href="<?php echo home_url() ?>/?s=&formats=<?php echo $formats_string; ?>&featured=true" class="theme--button primary md-hide"><?php _e('All features', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <?php articles_listing_in_columns($formats, 6, 4, 'thumbnail-medium', null, true); ?>
  </div>

  <div class="container pv--40">
    <?php banner(get_donate_link(), 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--56 pv--56 full-width', 'extra-large'); ?>
  </div>

  <div class="container full--width gap--40 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php articles_listing(get_default_formats(), 4, 8, true, 'thumbnail-medium', null, null, 'serial'); ?>
    </div>
    <div class="container centered pv--40">
      <a href="<?php echo home_url(); ?>/?s=&formats=<?php echo $formats_string; ?>&featured=true" class="theme--button primary"><?php _e('All features', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
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
formats_slider(array('post', 'custom-story', 'videos', 'podcats', 'specials'), 'The outstanding feature stories give one step forward', array(), 'text-center');
?>

<?php
$extra_params = array(
  'link_copy' => 'all stories',
  'link_url' => '',
);

topics_section('Explore features by topic such as', array('climate', 'oceans'), $extra_params);
?>

<div class="container full-width ph--40 in-column">
  <div id="section-features-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
      <h1><?php _e('Latest features', 'mongabay'); ?></h1>
      <a href="" class="theme--button primary"><?php _e('All features', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <?php articles_listing_in_columns('post', 6, 0, 'medium', null, true); ?>
  </div>

  <div class="container full-width pv--40">
    <?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
  </div>

  <div class="container full--width gap--40 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php articles_listing('post', 4, 8, true, 'medium', null, null, null); ?>
    </div>
    <div class="container centered pv--40">
      <a href="" class="theme--button primary"><?php _e('All series', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
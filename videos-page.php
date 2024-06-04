<?php

/**
 * Template Name: Videos Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<?php
$series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'global-forests'));
formats_slider('videos', 'Explore video stories from nature’s frontline.', array(), 'text-center');
?>

<?php topics_section('Explore engaging videos about pressing issues like', array('climate', 'oceans')); ?>

<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>

<div class="container full--width pv--40 gap--40 in-column">
  <div class="grid--4 gap--20 repeat">
    <?php articles_listing('videos', 8, 0, true, 'medium', null); ?>
  </div>
  <div class="container centered pv--40">
    <a href="" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
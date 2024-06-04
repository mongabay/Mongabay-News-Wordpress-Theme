<?php

/**
 * Template Name: Articles Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<div class="container content gap--40">
  <div class="column--80 latest-featured gap--8">
    <div class="section-title gap--16">
      <h4><?php _e('Top stories', 'mongabay'); ?></h4>
      <div class="divider"></div>
    </div>
    <?php featured_articles_listing('post', 10, 0, 'medium', 3, true, null); ?>
  </div>
  <div class="column--20 latest-news">
    <div class="section-title gap--8">
      <h4><?php _e('Latest', 'mongabay'); ?></h4>
      <div class="divider"></div>
    </div>
    <?php articles_listing(10, 0, false, 'medium', null); ?>
    <a class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>

<div class="container ph--40 pv--40">
  <?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<?php topics_section(); ?>

<?php featured_articles_slider(4, 5); ?>

<div class="container ph--40 pv--40">
  <?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<div class="section--highlight">
  <div class="container ph--40 pv--80 gap--20 in-column">
    <h1>Get quick glances with our brief <span>shorts</span></h1>
    <div class="grid--3 gap--40">
      <?php articles_listing_condensed('post', 6, 0, false, 'medium', null); ?>
    </div>
  </div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
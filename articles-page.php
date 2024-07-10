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
    <?php featured_articles_listing(array('post'), 10, 0, 'medium', 3, true, null, null); ?>
  </div>
  <div class="column--20 latest-news gap--8">
    <div class="section-title">
      <h4><?php _e('Latest', 'mongabay'); ?></h4>
      <div class="divider"></div>
    </div>
    <?php articles_listing('post', 10, 0, false, 'medium', null, null, null); ?>
    <a href="<?php echo home_url(); ?>/?s=&formats=post" class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>

<div class="container in-column ph--40 pv--40">
  <?php banner('', 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
  <?php topics_section(
    array('post'),
    'Read articles on topics such as',
    array('animals', 'forests', 'oceans', 'conservation', 'indigenous-peoples'),
    array('link_copy' => 'all topics', 'link_url' => home_url() . '/?s=&formats=post')
  ); ?>
</div>
<?php featured_articles_slider(4, 5); ?>

<div class="container ph--40 pv--40">
  <?php banner(getSubscribeLink(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<div class="section--highlight">
  <div class="container ph--40 pv--80 gap--20 in-column">
    <h1>Quickly stay updated with our news <span>shorts</span></h1>
    <div class="grid--3 gap--40">
      <?php articles_listing_condensed('short-article', 6, 0, false, 'medium', null); ?>
    </div>
  </div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
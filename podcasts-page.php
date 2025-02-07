<?php

/**
 * Template Name: Podcasts Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<?php
$series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'global-forests'));
formats_slider(array('podcasts'), 'Listen to Nature with thought-provoking podcasts', array(), 'text-center');
?>
<div class="container full-width ph--40 pv--40 in-column">
  <?php podcasts_topics_section('Explore podcasts about', array('forests', 'wildlife', 'oceans', 'climate', 'conservation-solutions'), array('link_copy' => 'all topics', 'link_url' => home_url() . '?s=&formats=podcasts')); ?>

  <div id="section-podcasts-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="<?php echo !wp_is_mobile() ? 'align-items: center; justify-content: space-between;' : ''; ?>">
      <h1><?php _e('Latest podcasts', 'mongabay'); ?></h1>
      <a href="<?php home_url(); ?>/?s=&formats=podcasts" class="theme--button primary md-hide"><?php _e('All podcasts', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <div class="grid--4 gap--20">
      <?php
      $banner = '
      <div class="banner gap--20 ph--20 pv--20 accent">
      <div class="inner">
        <div class="title">
          <h1 class="lh--tight">' . __('We’re a nonprofit', 'mongabay') . '</h1>
        </div>
        <div class="copy">
          ' . __('Help us tell impactful stories of biodiversity loss, climate change, and more', 'mongabay') . '
        </div>
        <a href="' . get_donate_link() . '" class="theme--button primary full-width">
          ' . __('Donate', 'mongabay') . '<span class="icon icon-right"></span>
        </a>
      </div>
    </div>
      ';
      ?>
      <?php articles_listing(array('podcasts'), 7, 0, true, 'medium', $banner, 4, null); ?>
    </div>
  </div>
  <div class="container pv--40">
    <?php banner(get_subscribe_link_local(get_current_blog_id()), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
  </div>
  <div class="container pv--40 gap--20 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php articles_listing(array('podcasts'), 4, 7, true, 'medium', null, null, null); ?>
    </div>
    <div class="container centered pv--40">
      <a href="<?php home_url(); ?>/?s=&formats=podcasts" class="theme--button primary"><?php _e('All podcasts', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
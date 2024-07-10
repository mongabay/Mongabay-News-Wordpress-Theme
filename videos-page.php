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
formats_slider(array('videos'), 'Watch unique videos that cut through the noise', array(), 'text-center');
?>

<div class="container in-column gap--20 ph--40">
  <?php topics_section(array('videos'), 'Explore videos about topics like', array('wildlife', 'indigenous-peoples', 'forests', 'oceans', 'agroecology'), array('link_copy' => 'all topics', 'link_url' => home_url() . '/?s=&formats=videos')); ?>

  <div id="section-videos-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
      <h1><?php _e('Latest videos', 'mongabay'); ?></h1>
      <a href="<?php echo home_url(); ?>/?s=&formats=videos" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <?php videos_latest(); ?>
  </div>

  <?php banner(getSubscribeLink(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>

  <div class="container full--width gap--40 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php articles_listing('videos', 8, 0, true, 'medium', null, null, null); ?>
    </div>
    <div class="container centered pv--40">
      <a href="<?php echo home_url(); ?>/?s=&formats=videos" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
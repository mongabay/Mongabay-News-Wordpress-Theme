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
formats_slider(array('videos'), 'Explore video stories from nature’s frontline.', array(), 'text-center');
?>

<div class="container full-width in-column gap--20 ph--40">
  <?php topics_section('Explore engaging videos about pressing issues like', array('climate', 'oceans'), array('link_copy' => 'all topics', 'link_url' => '')); ?>

  <div id="section-videos-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
      <h1><?php _e('Latest videos', 'mongabay'); ?></h1>
      <a href="" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <?php videos_latest(); ?>
  </div>

  <?php banner('https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>

  <div class="container full--width gap--40 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php articles_listing('videos', 8, 0, true, 'medium', null, null, null); ?>
    </div>
    <div class="container centered pv--40">
      <a href="" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
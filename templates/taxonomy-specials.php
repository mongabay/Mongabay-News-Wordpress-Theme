<?php

/**
 * The template for displaying taxonomy topics
 *
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header(); ?>
<?php
global $wp_query;
$wp_query->set('posts_per_page', 20);
$wp_query->set('paged', get_query_var('paged') ? get_query_var('paged') : 1);

$term = get_query_var('term');
$description = get_term_by('slug', $term, 'serial')->description;
$title = get_term_by('slug', $term, 'serial')->name;
$counter = 0;
?>
<div class="container in-column ph--40 gap--40">
  <div class="container full-width in-column section--highlight text-center gap--20 pv--40">
    <h1><?php echo $title; ?> <?php _e(' News', 'mongabay'); ?></h1>
    <?php if ($description) { ?>
      <p><?php echo strip_tags($description); ?></p>
    <?php } ?>
  </div>
  <div class="container full-width gap--40">
    <div class="column--80 latest-featured gap--8 gap--16">
      <?php
      $featured_image_query = new WP_Query(array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'cache_results' => true,
        'tax_query' => array(
          array(
            'taxonomy' => 'serial',
            'field' => 'slug',
            'terms' => $term,
          )
        ),
        'meta_query' => array(
          array(
            'key' => 'featured_as',
            'value' => 'featured',
            'compare' => '='
          )
        )
      ));

      if ($featured_image_query->have_posts()) {
        while ($featured_image_query->have_posts()) {
          $featured_image_query->the_post(); ?>
          <div class="article--container full-width">
            <a href="<?php the_permalink(); ?>">
              <div class="featured-image">
                <?php the_post_thumbnail('large'); ?>
              </div>
              <div class="title headline gap--8">
                <h1><?php the_title(); ?></h1>
                <div class="post-meta">
                  <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                  <span class="date"><?php the_time('j M Y'); ?></span>
                </div>
              </div>
            </a>
          </div>
      <? }
      }
      ?>
    </div>
    <div class="column--20 gap--40 latest-banners">
      <?php banner(get_subscribe_link(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'full-width outlined ph--20', ''); ?>
      <?php banner('', 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--20 full-width', ''); ?>
    </div>
  </div>

  <div class="container grid--4 repeat gap--16 pv-16">
    <?php
    // Start the Loop
    while (have_posts()) :
      the_post();
      $counter++;
    ?>
      <div class="article--container pv--8">
        <a href="<?php the_permalink(); ?>">
          <?php if (has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php echo get_icon(get_the_ID()); ?>
              <?php the_post_thumbnail('medium') ?>
            </div>
          <?php }; ?>
          <div class="title headline">
            <h3><?php the_title(); ?></h3>
          </div>
          <div class="post-meta pv--8">
            <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
            <span class="date"><?php the_time('j M Y'); ?></span>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
  <div class="pagination container pv--40 centered gap--20">
    <?php mongabay_pagination(); ?>
  </div>
  <div class="container section--highlight full-width pv-40">
    <?php $specials_latest = (array('indonesia-for-sale', 'problem-solved', 'conservation-effectiveness', 'satere-mawe', 'satere-mawe', 'satere-mawe', 'satere-mawe', 'satere-mawe'));
    specials_latest($specials_latest);
    ?>
  </div>
  <div class="container centered">
    <a href="<?php echo home_url(); ?>/?s=&formats=specials" class="theme--button primary"><?php _e('All Specials', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
</div>
<?php tools_slider(); ?>
<?php inspiration_banner(); ?>

<?php get_footer(); ?>
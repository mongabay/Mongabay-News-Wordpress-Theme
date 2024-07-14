<?php
get_header(); ?>
<?php
$description = get_the_content();
$title = get_the_title();

$post_id = get_the_ID();
$img_url = wp_get_attachment_url(get_post_thumbnail_id());
$specials_serie_slug = pods('specials', $post_id)->field('specials_series')['slug'];

$counter = 0;
?>
<div class="container in-column ph--40 gap--40">
  <div class="container full-width in-column section--highlight text-center gap--20 pv--40">
    <h1><?php echo $title; ?></h1>
    <?php if ($description) { ?>
      <p><?php echo strip_tags($description); ?></p>
    <?php } ?>
  </div>
  <div class="container full-width gap--40">
    <div class="column--80 latest-featured gap--8 gap--16">
      <div class="article--container full-width">
        <div class="featured-image">
          <?php the_post_thumbnail('large'); ?>
        </div>
      </div>
    </div>
    <div class="column--20 gap--40 latest-banners">
      <?php banner(get_subscribe_link(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'full-width outlined ph--20 pv--20', ''); ?>
      <?php banner(get_donate_link(), 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--20 full-width', ''); ?>
    </div>
  </div>

  <div class="container grid--4 repeat gap--16 pv-16">
    <?php
    $posts_per_page = 12;

    $args = array(
      'post_type' => 'post',
      'posts_per_page' => $posts_per_page,
      'cache_results' => true,
      'tax_query' => array(
        array(
          'taxonomy' => 'serial',
          'field' => 'slug',
          'terms' => $specials_serie_slug,
        )
      )
    );

    $serail_posts_query = new WP_Query($args);

    if ($serail_posts_query->have_posts()) {
      while ($serail_posts_query->have_posts()) :
        $serail_posts_query->the_post();

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
    <?php endwhile;
    }
    wp_reset_postdata();
    ?>
  </div>
  <?php if ($serail_posts_query->found_posts > $posts_per_page) { ?>
    <div id="posts" class="container grid--4 repeat gap--16 pv-16"></div>
    <div class="container centered pv--40">
      <a class="theme--button outlined load-more-button" data-post-type="post" data-taxonomy="serial" data-terms="<?php echo $specials_serie_slug; ?>" data-per-page="12"><?php _e('Load more', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  <?php } ?>
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
<?php
function short_videos_latest($is_landing_page = false)
{
  $args = array(
    'post_type' => 'short-article',
    'posts_per_page' => 5,
    'post_status' => 'publish',
    'cache_results' => true,
    'tax_query' => array(
      array(
        'taxonomy' => 'shorts_format',
        'field' => 'slug',
        'terms' => array('video'),
      ),
    ),
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) { ?>
    <div id="section-short-videos-latest" class="container pv--40 gap--20 in-column">
      <?php if (!$is_landing_page) { ?>
        <div class="container in-row full-width section--headline" style="<?php echo !wp_is_mobile() ? 'align-items: center; justify-content: space-between;' : ''; ?>">
          <h1><?php _e('Latest short videos', 'mongabay'); ?></h1>
          <a href="<?php echo esc_url(home_url('?s=&formats=short_video')); ?>" class="theme--button primary md-hide"><?php _e('All short videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
        </div>
      <?php } else { ?>
        <div class="section-title gap--16">
          <h4><?php _e('Latest short videos', 'mongabay'); ?></h4>
          <div class="divider"></div>
        </div>
      <?php } ?>

      <div class="grid--5 gap--20 short-videos-listing shorts short-videos-horizontal-scroll" id="short-videos-listing" data-page="1" data-loading="false" data-has-more="true">
        <?php while ($query->have_posts()) {
          $query->the_post();
          $id = get_the_ID();
          $embed_url = get_post_meta($id, 'video_link', true);
          $article_link = get_post_meta($id, 'article_link', true);
          $link_url_share = home_url('/shorts/#/' . $id);
        ?>
          <div class="article--container shorts-trigger shorts-video-card" data-articlelink="<?php echo esc_url($article_link); ?>" data-shareurl="<?php echo esc_url($link_url_share); ?>" data-media-format="video" data-embed-url="<?php echo esc_url($embed_url); ?>">
            <div class="featured-image shorts-video-featured-image">
              <?php echo get_icon($id); ?>
              <?php if (has_post_thumbnail()) { ?>
                <?php the_post_thumbnail('full'); ?>
              <?php } ?>
              <div class="img-overlay"></div>
              <div class="shorts-video-overlay">
                <div class="title headline">
                  <h3><?php the_title(); ?></h3>
                </div>
                <div class="post-meta">
                  <span class="byline"><?php echo getPostBylines($id); ?></span>
                  <span class="date"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'mongabay'); ?></span>
                </div>
              </div>
            </div>
            <div class="post-excerpt hidden">
              <?php the_content(); ?>
            </div>
            <div class="shorts-media-embed hidden">
              <?php mongabay_render_embed_url($embed_url); ?>
            </div>
          </div>
        <?php } ?>
        <div class="short-videos-loading" id="short-videos-loading" style="display: none;">
          <div class="loading-spinner"><?php _e('Loading...', 'mongabay'); ?></div>
        </div>
      </div>
      <?php if ($is_landing_page) { ?>
        <div class="container justify-right">
          <a href="<?php echo esc_url(home_url('?s=&formats=short_video')); ?>" class="theme--button outlined"><?php _e('All short videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
        </div>
      <?php } ?>
      <?php // get_shorts_dialog(); ?>
    </div>
<?php }
  wp_reset_postdata();
}

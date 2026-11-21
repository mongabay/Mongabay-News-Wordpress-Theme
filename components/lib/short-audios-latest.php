<?php
function short_audios_latest()
{
  $args = array(
    'post_type' => 'short-article',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'cache_results' => true,
    'tax_query' => array(
      array(
        'taxonomy' => 'shorts_format',
        'field' => 'slug',
        'terms' => array('audio'),
      ),
    ),
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) { ?>
    <div id="section-short-audios-latest" class="container pv--40 gap--20 in-column">
      <div class="container in-row full-width section--headline" style="<?php echo !wp_is_mobile() ? 'align-items: center; justify-content: space-between;' : ''; ?>">
        <h1><?php _e('Latest short audio', 'mongabay'); ?></h1>
        <a href="<?php echo esc_url(home_url('/?s=&formats=short_audio')); ?>" class="theme--button primary md-hide"><?php _e('All short audios', 'mongabay'); ?><span class="icon icon-right"></span></a>
      </div>
      <div class="grid--3 gap--20 short-audios-listing shorts">
        <?php while ($query->have_posts()) {
          $query->the_post();
          $id = get_the_ID();
          $embed_url = get_post_meta($id, 'audio_link', true);
        ?>
          <div class="article--container shorts-audio-card rounded">
            <div class="shorts-audio-top">
              <div class="shorts-audio-info">
                <div class="shorts-audio-label">
                  <span>&#10022;</span> <?php _e('AI Voice Narration', 'mongabay'); ?>
                </div>
                <div class="title headline">
                  <h3><?php the_title(); ?></h3>
                </div>
                <div class="post-meta">
                  <span class="byline"><?php echo getPostBylines($id); ?></span>
                  <span class="date"><?php the_time('j M Y'); ?></span>
                </div>
              </div>
              <?php if (has_post_thumbnail()) { ?>
                <div class="shorts-audio-thumbnail">
                  <?php echo get_icon($id); ?>
                  <?php the_post_thumbnail('thumbnail'); ?>
                </div>
              <?php } ?>
            </div>
            <div class="shorts-audio-sticky-shell">
              <?php if (has_post_thumbnail()) { ?>
                <div class="shorts-audio-thumbnail sticky-thumbnail">
                  <?php the_post_thumbnail('thumbnail'); ?>
                </div>
              <?php } ?>
              <div class="shorts-audio-sticky-title headline">
                <h3><?php the_title(); ?></h3>
              </div>
              <button type="button" class="shorts-audio-sticky-close" aria-label="<?php esc_attr_e('Close player', 'mongabay'); ?>">
                <span class="icon icon-cancel"></span>
              </button>
              <div class="shorts-audio-sticky-player-slot"></div>
            </div>
            <div class="shorts-audio-embed bg-theme-accent">
              <div class="shorts-audio-player-host">
                <?php mongabay_render_embed_url($embed_url); ?>
              </div>
            </div>
            <div class="post-excerpt hidden">
              <?php the_content(); ?>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php get_shorts_dialog(); ?>
    </div>
<?php }
  wp_reset_postdata();
}

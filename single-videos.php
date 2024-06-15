<?php get_header(); ?>
<main role="main">
  <?php
  $post_id = get_the_ID();
  $translator = get_post_meta($post_id, "translated_by", true);
  $adaptor = get_post_meta($post_id, "adapted_by", true);
  $translated_adapted = get_post_meta($post_id, "translated_adapted", true);
  $commentary = get_post_meta($post_id, "commentary", true);
  $analysis = get_post_meta($post_id, "analysis_by", true);
  $topics = wp_get_post_terms($post_id, 'topic');
  $serial = wp_get_post_terms($post_id, 'serial');
  $byline_terms = wp_get_post_terms($post_id, 'byline');
  $avatar = null;

  if (!empty($byline_terms)) {
    $avatar = get_term_meta($byline_terms[0]->term_id, 'cover_image_url', true);
  }

  $video_url = get_post_meta($post_id, 'video_source', true);
  ?>
  <div class="container in-column ph--40">
    <?php article_headline(); ?>
    <?php if (!empty($video_url)) : ?>
      <div class="container full-width video pv--24">
        <?php
        echo do_shortcode($wp_embed->autoembed(pods('videos', get_the_ID())->field('video_source')));
        ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="inner">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="position: relative">
          <div class="container in-column">
            <div id="transcript">
              <?php mongabay_sanitized_content($post_id); ?>
            </div>
            <div id="expander-container" class="container full-width">
              <a class="content-expander text-center"><span><?php _e('Read full transcript', 'mongabay'); ?></span></a>
            </div>
          </div>
          <div id="single-article-footer">
            <?php article_credits($post_id); ?>
            <div id="single-article-tags">
              <?php article_terms($post_id); ?>
            </div>
          </div>
          <span class="article-comments"><a href=""></a></span>
          <?php mongabay_comments(); ?>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <article>
        <h1><?php _e('Sorry, nothing to display.', 'mongabay'); ?></h1>
      </article>
    <?php endif; ?>
    <script>
      jQuery(document).ready(function() {
        const wrapperHeight = jQuery('#transcript').height();
        console.log({
          wrapperHeight
        })
        jQuery('#expander-container .content-expander').click(function() {
          jQuery('#transcript').toggleClass('visible');
          jQuery(this).toggleClass('visible');
        });
      });
    </script>
  </div>

  <div class="container ph--40 pv--40 in-column">
    <div class="container full-width pv--40">
      <?php banner('', 'We are nonprofit.', 'Help us tell stories of biodiversity loss, climate change & socio-environmental injustice.', 'Donate', 'accent full-width pv--56', 'extra-large'); ?>
    </div>
    <div class="container pv--40">
      <?php series_articles_listing(false);
      ?>
    </div>
    <div class="container in-column pv--40 gap--40">
      <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
        <h1><?php _e('Latest videos', 'mongabay'); ?></h1>
        <a href="<?php home_url(); ?>/?s=&format=videos" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
      </div>
      <?php videos_latest(); ?>
      <?php banner('https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
    </div>
    <div class="grid--4 gap--20 repeat pv--40">
      <?php articles_listing('videos', 8, 0, true, 'medium', false, false, null); ?>
    </div>
    <div class="container centered">
      <a href="<?php home_url(); ?>/?s=&format=videos" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
  <div class="container full-width pv--40">
    <?php inspiration_banner(); ?>
  </div>
</main>
<?php get_footer(); ?>
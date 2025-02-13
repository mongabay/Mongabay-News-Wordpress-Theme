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
  $podcast = get_post_meta($post_id, 'podcast_embed', true);
  ?>
  <div class="container in-row ph--40 pv--80 gap--40">
    <div class="column--30">
      <?php if (has_post_thumbnail($post_id)) : ?>
        <?php the_post_thumbnail('medium', ['class' => 'full-height rounded']); ?>
      <?php endif; ?>
    </div>
    <div class="column--70 in-column gap--20">
      <?php
      article_headline();
      echo do_shortcode('[iframe height="200" scrolling="no" src="' . pods('podcasts', get_the_ID())->field('podcast_embed') . '"]');
      ?>
    </div>
  </div>
  <div class="container in-row ph--40 gap--40">
    <div class="column--80 in-column">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="position: relative">
            <div class="container in-column ph--40">
              <?php mongabay_sanitized_content($post_id); ?>
              <div id="transcript" class="container in-column gap--40">
                <?php
                $transcript = pods('podcasts', get_the_ID())->field('transcript');
                if (!empty($transcript)) {
                  echo '<div><h1>';
                  _e('Transcript', 'mongabay');
                  echo '</h1>';
                  echo '<i><small>';
                  _e('Notice: Transcripts are machine and human generated and lightly edited for accuracy. They may contain errors.', 'mongabay');
                  echo '</small></i></div>';
                  echo pods_field_display('transcript');
                }
                ?>
              </div>
              <div id="expander-container" class="transcript container full-width">
                <a class="content-expander text-center"><span><?php _e('Read full transcript', 'mongabay'); ?></span></a>
              </div>
            </div>
            <div id="single-article-footer" class="container ph--40 in-column">
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

          jQuery('#expander-container.transcript .content-expander').click(function() {
            jQuery('#transcript').toggleClass('visible');
            jQuery(this).toggleClass('visible');
          });
        });
      </script>
    </div>
    <div class="column--20 in-column gap--20">
      <?php banner(get_donate_link(), 'We’re a nonprofit.', 'Help us tell stories of biodiversity loss, climate change & socio-environmental injustice.', 'Donate', 'accent full-width ph--20 pv--20', ''); ?>
      <?php banner(get_subscribe_link_local(get_current_blog_id()), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'outlined ph--20 pv--20 full-width', ''); ?>
    </div>
  </div>

  <div class="container ph--40 pv--40 in-column">
    <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
      <h1><?php _e('Latest podcasts', 'mongabay'); ?></h1>
      <a href="<?php echo home_url(); ?>/?s=&formats=podcasts" class="theme--button primary"><?php _e('All podcasts', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>

    <div class="grid--4 gap--20">
      <?php
      $banner = '
      <div class="banner gap--20 ph--20 pv--20 accent">
      <div class="inner">
        <div class="title">
          <h1>' . (get_enabled_features('donate') ? __('We’re a nonprofit', 'mongabay') : 'Free and Open') . '</h1>
        </div>
        <div class="copy">'
        . __('Help us tell impactful stories of biodiversity loss, climate change, and more', 'mongabay') . '
        </div>
        <a href="' . (get_enabled_features('donate') ? get_donate_link() : 'https://mongabay.org/about') . '" class="theme--button primary full-width">'
        . (get_enabled_features('donate') ? __('Donate', 'mongabay') : 'Learn more') . '<span class="icon icon-right"></span>
        </a>
      </div>
    </div>
      ';
      ?>
      <?php articles_listing(array('podcasts'), 7, 0, true, 'medium', $banner, 4, null); ?>
    </div>

    <div class="container full-width pv--40">
      <?php banner(get_subscribe_link_local(get_current_blog_id()), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
    </div>
    <div class="container pv--40 gap--20 in-column">
      <div class="grid--4 gap--20 repeat">
        <?php articles_listing(array('podcasts'), 4, 7, true, 'medium', null, null, null); ?>
      </div>
      <div class="container centered pv--40">
        <a href="<?php echo home_url(); ?>/?s=&formats=podcasts" class="theme--button primary"><?php _e('All podcasts', 'mongabay'); ?><span class="icon icon-right"></span></a>
      </div>
    </div>
  </div>
  <div class="container full-width pv--40">
    <?php inspiration_banner(); ?>
  </div>
</main>
<?php get_footer(); ?>
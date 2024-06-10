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
  $avatar = get_term_meta($byline_terms[0]->term_id, 'cover_image_url', true);
  $video_url = get_post_meta($post_id, 'video_source', true);
  ?>
  <div class="container in-column ph--40">
    <?php article_headline(); ?>
      <?php if (!empty($video_url)) : ?>
        <div class="container">
          <?php
          echo do_shortcode($wp_embed->autoembed(pods('videos', get_the_ID())->field('video_source')));
          ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="single">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            echo '<div class="container full-width text-center">';
            echo '<a class="toggle-bulletpoints text-center"><span>' . __('Read full transcript', 'mongabay') . '<span class="icon icon-down-open"></span></span></a>';
            echo '</div>';

            echo '</div>';
            ?>
            <script>
              (function($) {
                $(document).ready(function() {
                  $('.bulletpoints .toggle-bulletpoints').click(function() {
                    $(this).prev('ul').find('li:nth-child(n+3)').toggle();
                    if ($(this).prev('ul').find('li:nth-child(n+3)').is(':visible')) {
                      $(this).addClass('bullets-visible');
                      $('span.icon .icon-down-open').removeClass('icon-down-open').addClass('icon-up-open');
                    } else {
                      $(this).removeClass('bullets-visible');
                      $('span.icon .icon-down-open').removeClass('icon-up-open').addClass('icon-down-open');
                    }
                  });
                });
              })(jQuery);
            </script>

            <?php mongabay_sanitized_content($post_id); ?>

            <div id="single-article-footer">
              <div id="single-article-meta">
                <div class="about-editor-translator">
                  <div class="section-title gap--16">
                    <h4><?php _e('Credits ', 'mongabay'); ?> </h4>
                    <div class="divider"></div>
                  </div>
                  <div class="author-avatar">
                    <?php $author_avatar = get_avatar(get_the_author_meta('ID'), 32); ?>
                    <?php if ($author_avatar) {
                      echo $author_avatar;
                    } else {
                      echo '<span class="meta-author-circle"></span>';
                    } ?>
                  </div>
                  <div class="author-info">
                    <span>
                      <?php the_author_posts_link(); ?>
                      <?php _e('Contributor ', 'mongabay'); ?>
                    </span>
                  </div>

                  <?php
                  if ($translated_adapted == 'adapted' || $translated_adapted == 'translated') {

                    if ($translated_adapted == 'adapted' && !empty($adaptor)) {
                      $string_title = 'Adaptor';
                      $translator_adaptor = $adaptor;
                    } elseif ($translated_adapted == 'translated' && !empty($translator)) {
                      $string_title = 'Translator';
                      $translator_adaptor = $translator;
                    }
                    $translator_adaptor_slug = $translator_adaptor['slug'];
                    $translator_adaptor_name = $translator_adaptor['name'];
                    $translator_adaptor_id = $translator_adaptor['term_id'];

                    $adaptor_avatar = get_term_meta($translator_adaptor_id, 'cover_image_url', true);

                    echo '<div class="author-avatar">';
                    if (!empty($adaptor_avatar)) {
                      echo '<img src="' . esc_url($adaptor_avatar) . '" alt="Cover Image">';
                    } else {
                      echo '<span class="meta-author-circle"></span>';
                    }
                    echo '</div>';
                    echo '<div class="author-info">';
                    echo '<a href="' . home_url('/') . 'by/' . $translator_adaptor_slug . '">' . $translator_adaptor_name . '</a>';
                    _e($string_title, 'mongabay');
                    echo '</div>';
                  }
                  ?>
                </div>
                <div id="single-article-tags">
                  <?php echo get_the_term_list($post_id, 'topic', '', ', '); ?><br><BR>
                  <?php echo get_the_term_list($post_id, 'location', '', ', '); ?><br><BR>
                  <?php echo get_the_term_list($post_id, 'entity', '', ', '); ?>
                </div>
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
    </div>

  </div>
  <div class="container ph--40 pv--40 in-column">
    <div class="container full-width pv--40">
      <?php banner('', 'We are nonprofit.', 'Help us tell stories of biodiversity loss, climate change & socio-environmental injustice.', 'Donate', 'accent full-width pv--56', 'extra-large'); ?>
    </div>
    <div class="container pv--40">
      <?php // series_articles_listing(); 
      ?>
    </div>
    <div class="container in-column pv--40 gap--40">
      <?php videos_latest(); ?>
      <?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
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
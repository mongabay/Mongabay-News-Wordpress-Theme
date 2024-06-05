<?php get_header(); ?>
<main role="main">
  <?php
  $post_id = get_the_ID();
  $commentary = get_post_meta($post_id, "commentary", true);
  $analysis = get_post_meta($post_id, "analysis_by", true);
  $topics = wp_get_post_terms($post_id, 'topic');
  $serial = wp_get_post_terms($post_id, 'serial');
  $legacy = get_post_meta($post_id, 'mongabay_post_legacy_status', true);
  $img_url = wp_get_attachment_url(get_post_thumbnail_id());
  $byline_terms = wp_get_post_terms($post_id,'byline');
  $avatar = get_term_meta($byline_terms[0]->term_id,'cover_image_url',true);
  ?>
  <div class="container in-column ph--40">
    <div id="headline">
      <div class="article-headline">
        <?php
        if ($serial) {
          echo '<p>';
          _e('Mongabay Series: ', 'mongabay');
          echo get_the_term_list($post_id, 'serial', '', ', ', '');
          echo '</p>';
        }
        ?>
        <h1><?php the_title(); ?></h1>
        <?php
        if (mongabay_wp_is_mobile()) {
          echo '<div class="social">';
          // get_template_part('partials/section', 'social');
          echo '</div>';
        }
        ?>
      </div>
      <div class="single-article-meta">
        <div class="about-author">
          <div class="author-avatar">
              <?php if($avatar){ echo '<img src="' . $avatar . '" alt="cover image" style="max-width:48px;height:auto;">';
                } else { echo '<span class="meta-author-circle"></span>';
                } ?>
          </div>
          <div class="author-info">
            <?php echo get_the_term_list($post_id, 'byline', '', ', ', ''); ?><?php the_time('j F Y'); ?>
          </div>
        </div>
        <?php $comments_number = get_comments_number();
          if ( $comments_number > 1 ){
            printf( esc_html__('%s Comments', 'mongabay'), get_comments_number_text( '0', '1', '%' ) );
          }
          else {
            esc_html_e( '1 Comment', 'mongabay' );
          } ?>
        <?php if ($commentary == '1' || $commentary == 'yes') _e('Commentary ', 'mongabay');
        if ($analysis == '1' || $analysis == 'yes') _e('Analysis ', 'mongabay'); ?>        

        <?php
        if (!mongabay_wp_is_mobile()) {
          echo '<div class="social">';
          get_template_part('partials/section', 'social');
          echo '</div>';
        }
        ?>
      </div>
      <div class="featured-area">
        <?php echo pods_field_display( 'video_source' ); ?>
      </div>
    </div>
    <?php if (!empty($img_url)) : ?>
      <div class="row article-cover-image no-gutters">
        <?php
        if (wp_is_mobile()) {
          $coversize = 'medium';
        } elseif (strlen(get_the_post_thumbnail_url($post_id, 'cover-image-retina')) > 0) {
          $coversize = 'cover-image-retina';
        } else {
          $coversize = 'large';
        }
        ?>
        <div class="col-lg-12" style="background: url('<?php echo get_the_post_thumbnail_url($post_id, $coversize) ?>');background-size: cover; background-position: center"></div>

      </div>
    <?php endif; ?>


    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <!-- article -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php
          $mog_count = 0;
          for ($n = 0; $n < 4; $n++) {
            $single_bullet = get_post_meta($post_id, "mog_bullet_" . $n . "_mog_bulletpoint", true);
            if (!empty($single_bullet)) {
              $mog_count = $mog_count + 1;
            }
          }
          if ((int)$mog_count > 0 && get_post_meta($post_id, "mog_bullet_0_mog_bulletpoint", true)) {
            echo '<div class="bulletpoints"><ul>';
            for ($i = 0; $i < $mog_count; $i++) {

              echo "<li><em>" . get_post_meta($post_id, "mog_bullet_" . $i . "_mog_bulletpoint", true) . "</em></li>";
            }
            echo "</ul></div>";
          }
          ?>
          <?php mongabay_sanitized_content($post_id); ?>
          <div id="single-article-footer">
            <div id="single-article-meta">
              <span>
                <?php _e('Article published by ', 'mongabay'); ?><?php the_author_posts_link(); ?></span>
              <span class="article-comments"><a href=""></a></span>
            </div>
            <div id="single-article-tags">
              <?php echo get_the_term_list($post_id, 'topic', '', ', '); ?><br><BR>
              <?php echo get_the_term_list($post_id, 'location', '', ', '); ?><br><BR>
              <?php echo get_the_term_list($post_id, 'entity', '', ', '); ?>
            </div>
            <a href="amp/?print" style="text-align:center;display:block;width: 45px;color: inherit;text-transform: uppercase;margin: 1em 0;font-size: 0.8em;"><svg style="width:45px;height:35px;" class="icon" fill="#669a00">
                <use xlink:href="#print"></use>
              </svg>Print</a>
          </div>
          <?php mongabay_comments(); ?>

        </article>
        <!-- /article -->
      <?php endwhile; ?>
    <?php else : ?>
      <!-- article -->
      <article>
        <h1><?php _e('Sorry, nothing to display.', 'mongabay'); ?></h1>
      </article>
      <!-- /article -->
    <?php endif; ?>
  </div>
  <?php // series_articles_listing(); ?>
  <div class="container centered pv--40">
    <?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
  </div>
  <div id="section-videos-latest" class="container pv--40 gap--20 in-column">
    <div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
      <h1><?php _e('Latest videos', 'mongabay'); ?></h1>
      <a href="" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
    <?php videos_latest(); ?>
  </div>
  <?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>

  <div class="container full--width pv--40 gap--40 in-column">
    <div class="grid--4 gap--20 repeat">
      <?php // articles_listing('videos', 8, 0, true, 'medium', null); ?>
    </div>
    <div class="container centered pv--40">
      <a href="" class="theme--button primary"><?php _e('All videos', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
  <?php inspiration_banner(); ?>
</main>
<?php get_footer(); ?>

<?php
function mongabay_render_embed_url($url)
{
  $url = trim((string) $url);

  if (empty($url)) {
    return;
  }

  // Direct audio/video file — use WordPress native media shortcodes.
  $audio_extensions = array('mp3', 'ogg', 'oga', 'flac', 'wav', 'm4a', 'aac', 'wma', 'opus');
  $video_extensions = array('mp4', 'm4v', 'webm', 'ogv', 'wmv', 'avi', 'mov', 'flv');
  $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

  if (in_array($ext, $audio_extensions)) {
    echo do_shortcode('[audio src="' . esc_url($url) . '"]');
    return;
  }

  if (in_array($ext, $video_extensions)) {
    echo do_shortcode('[video src="' . esc_url($url) . '"]');
    return;
  }

  // oEmbed provider URL (YouTube, SoundCloud, Spotify, etc.) — use WP_Embed
  // which is the same path WordPress uses when processing post content.
  global $wp_embed;
  $embed_html = $wp_embed->run_shortcode('[embed]' . $url . '[/embed]');

  if (!empty(trim(strip_tags($embed_html)))) {
    echo $embed_html;
    return;
  }

  // Last-resort: direct oEmbed API call with generous width.
  $oembed_html = wp_oembed_get($url, array('width' => 800));
  if (!empty($oembed_html)) {
    echo $oembed_html;
  }
}

function shorts_article_card($id, $is_large = false, $title_class = '', $idx = 0, $post_format, $same_background = false)
{
  $backgrounds = array(
    1 => ' bg-theme-secondary',
    2 => ' bg-theme-gray',
    3 => ' bg-theme-accent',
    4 => ' bg-brand-color',
    5 => ' bg-theme-gray',
    6 => ' bg-theme-gray',
    7 => ' bg-theme-secondary',
    8 => ' bg-theme-accent',
    9 => ' bg-theme-gray',
  );

  $article_link = get_post_meta($id, 'article_link', true);
  $link_url_share = home_url() . '/shorts/#/' . $id;

  // $backgrounds = array(
  //   1 => ' bg-theme-secondary',
  //   2 => ' bg-theme-gray',
  //   3 => ' bg-theme-gray',
  //   4 => ' bg-theme-accent',
  //   5 => ' bg-brand-color',
  //   6 => ' bg-theme-accent',
  //   7 => ' bg-theme-gray',
  //   8 => ' bg-theme-secondary',
  //   9 => ' bg-theme-gray',
  // );
?><?php
  if ($post_format === 'video') {
    $embed_url = get_post_meta($id, 'video_link', true);
  ?>
<div class="article--container shorts-trigger shorts-video-card" data-articlelink="<?php echo esc_url($article_link); ?>" data-shareurl="<?php echo esc_url($link_url_share); ?>" data-media-format="video" data-embed-url="<?php echo esc_url($embed_url); ?>">
  <div class="featured-image shorts-video-featured-image">
    <div class="shorts-video-preview">
      <?php mongabay_render_embed_url($embed_url); ?>
    </div>
    <div class="shorts-video-click-blocker"></div>
    <div class="img-overlay"></div>
    <div class="shorts-video-overlay">
      <div class="title headline <?php echo $title_class; ?>">
        <?php echo $is_large ? '<h2>' : '<h3>' ?>
        <?php the_title(); ?>
        <?php echo $is_large ? '</h2>' : '</h3>' ?>
      </div>
      <div class="post-meta">
        <span class="byline"><?php echo getPostBylines($id); ?></span>
        <span class="date"><?php the_time('j M Y'); ?></span>
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
<?php } elseif ($post_format === 'audio') {
    $embed_url = get_post_meta($id, 'audio_link', true);
?>
  <div class="article--container shorts-audio-card rounded">
    <div class="shorts-audio-top">
      <div class="shorts-audio-info">
        <!-- <div class="shorts-audio-label">
          <span>&#10022;</span> <?php //_e('AI Voice Narration', 'mongabay'); ?>
        </div> -->
        <div class="title headline">
          <?php echo $is_large ? '<h2 class="' . $title_class . '">' : '<h3 class="' . $title_class . '">' ?>
          <?php the_title(); ?>
          <?php echo $is_large ? '</h2>' : '</h3>' ?>
        </div>
        <div class="post-meta">
          <span class="byline"><?php echo getPostBylines($id); ?></span>
          <span class="date"><?php the_time('j M Y'); ?></span>
        </div>
      </div>
      <?php if (has_post_thumbnail()) { ?>
        <div class="shorts-audio-thumbnail">
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
<?php } else { ?>
  <div class="article--container shorts-trigger" data-articlelink="<?php echo $article_link; ?>" data-shareurl="<?php echo $link_url_share; ?>">
    <div class="title left headline rounded-top <?php echo $is_large ? 'ph--80 pv--80' : 'ph--30 pv--30';
                                                echo $same_background ? $backgrounds[2] : $backgrounds[$idx]; ?>">
      <?php echo $is_large ? '<h2 class="' . $title_class . '">' : '<h3 class="' . $title_class . '">' ?>
      <?php the_title(); ?>
      <?php echo $is_large ? '</h2>' : '</h3>' ?>
    </div>
    <div class="post-meta hidden">
      <span class="byline"><?php echo getPostBylines($id); ?></span>
      <span class="date"><?php the_time('j M Y'); ?></span>
    </div>
    <div class="post-excerpt hidden">
      <?php the_content(); ?>
    </div>
    <?php the_post_thumbnail('thumbnail-medium'); ?>
  </div>
<?php } ?>
<?php
}

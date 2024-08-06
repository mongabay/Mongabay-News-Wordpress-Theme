<?php
function article_credits(int $post_id)
{
  $translator = get_post_meta($post_id, "translated_by", true);
  $adaptor = get_post_meta($post_id, "adapted_by", true);
  $translated_adapted = get_post_meta($post_id, "translated_adapted", true);
  $job_title = get_the_author_meta('job_title');
  $is_video_type = get_post_type($post_id) === 'videos';
  $string_title = '';
  $translator_adaptor = null;
  $$translator_adaptor_slug = null;
  $translator_adaptor_name = null;
  $translator_adaptor_id = null;
?>
  <div class="container in-column about-editor-translator gap--40 pv--80">
    <div class="section-title gap--16">
      <h4><?php _e('Credits ', 'mongabay'); ?> </h4>
      <div class="divider"></div>
    </div>
    <div class="container grid--3 repeat gap--40">
      <div class="in-row gap--16">
        <div class="author-avatar">
          <?php $author_avatar = get_avatar(get_the_author_meta('ID'), 32); ?>
          <?php if ($author_avatar) {
            echo $author_avatar;
          } else {
            echo '<span class="meta-author-circle"></span>';
          } ?>
        </div>
        <div class="extra-info">
          <?php the_author_posts_link(); ?>
          <?php if ($job_title) { ?>
            <span><?php _e($job_title, 'mongabay'); ?></span>
          <?php } else { ?>
            <span><?php _e('Editor', 'mongabay'); ?></span>
          <?php } ?>
        </div>
      </div>
      <?php
      if (!$is_video_type && ($translated_adapted == 'adapted' || $translated_adapted == 'translated')) {
        if ($translated_adapted == 'adapted' && !empty($adaptor)) {
          $string_title = 'Adaptor';
          $translator_adaptor = $adaptor;
        } elseif ($translated_adapted == 'translated' && !empty($translator)) {
          $string_title = 'Translator';
          $translator_adaptor = $translator;
        }

        if ($translator_adaptor) {
          $translator_adaptor_slug = $translator_adaptor['slug'];
          $translator_adaptor_name = $translator_adaptor['name'];
          $translator_adaptor_id = $translator_adaptor['term_id'];

          if ($translator_adaptor_id) {
            $adaptor_avatar = get_term_meta($translator_adaptor_id, 'cover_image_url', true);
          }
        }
      ?>
        <div class="in-row gap--16">
          <div class="author-avatar">
            <?php
            if ($adaptor_avatar) { ?>
              <img src="<?php echo esc_url($adaptor_avatar); ?>" />
            <?php } else { ?>
              <span class="meta-author-circle"></span>
            <?php } ?>
          </div>
          <div class="extra-info">
            <?php if ($translator_adaptor_slug && $translator_adaptor_name) { ?>
              <a href="<?php echo home_url('/') . 'by/' . $translator_adaptor_slug; ?>">
                <?php echo $translator_adaptor_name; ?>
              </a>
            <?php } ?>
            <span><?php _e($string_title, 'mongabay'); ?></span>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
<? } ?>
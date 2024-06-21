<?php
function article_credits(int $post_id)
{
  $translator = get_post_meta($post_id, "translated_by", true);
  $adaptor = get_post_meta($post_id, "adapted_by", true);
  $translated_adapted = get_post_meta($post_id, "translated_adapted", true);
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
          <h4><?php the_author_posts_link(); ?></h4>
          <span><?php _e('Contributor ', 'mongabay'); ?></span>
        </div>
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

        $adaptor_avatar = get_term_meta($translator_adaptor_id, 'cover_image_url', true); ?>
        <div class="in-row gap--16">
          <div class="author-avatar">
            <?php
            if (!empty($adaptor_avatar)) { ?>
              <img src="' . esc_url($adaptor_avatar) . '" alt="Cover Image">'
            <?php } else { ?>
              <span class="meta-author-circle"></span>
            <?php } ?>
          </div>
          <div class="extra-info">
            <a href="<?php echo home_url('/') . 'by/' . $translator_adaptor_slug; ?>">
              <?php echo $translator_adaptor_name; ?>
            </a>
            <span><?php _e($string_title, 'mongabay'); ?></span>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
<? } ?>
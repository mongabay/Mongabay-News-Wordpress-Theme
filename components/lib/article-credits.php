<?php
function article_credits()
{
  $post_id = get_the_ID();
  $translator = get_post_meta($post_id, "translated_by", true);
  $adaptor = get_post_meta($post_id, "adapted_by", true);
  $translated_adapted = get_post_meta($post_id, "translated_adapted", true);
?>
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
<? } ?>
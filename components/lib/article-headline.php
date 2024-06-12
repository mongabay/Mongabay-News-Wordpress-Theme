<?php
function article_headline()
{
  $post_id = get_the_ID();
  $byline_terms = wp_get_post_terms($post_id, 'byline');
  $avatar = null;

  if (!empty($byline_terms)) {
    $avatar = get_term_meta($byline_terms[0]->term_id, 'cover_image_url', true);
  }
?>
  <div class="container in-column gap--16 article-headline">
    <h1><?php the_title(); ?></h1>
    <div class="container in-column full-width single-article-meta">
      <div class="about-author">
        <div class="author-avatar">
          <?php if ($avatar) {
            echo '<img src="' . $avatar . '" alt="cover image" style="max-width:48px;height:auto;">';
          } else {
            echo '<span class="meta-author-circle"></span>';
          } ?>
        </div>
        <div class="author-info">
          <?php echo get_the_term_list($post_id, 'byline', '', ', ', ''); ?><?php the_time('j F Y'); ?>
        </div>
      </div>
      <div class="container social gap--4">
        <?php $comments_number = get_comments_number();
        echo '<a class="theme--button simple secondary">';

        if ($comments_number > 1) {
          printf(esc_html__('%s Comments', 'mongabay'), get_comments_number_text('0', '1', '%'));
        } else {
          esc_html_e('1 Comment', 'mongabay');
        }
        echo '</a>';
        ?>

        <?php social_share(); ?>
      </div>

    </div>
  </div>
<? } ?>
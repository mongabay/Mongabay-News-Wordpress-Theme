<?php
function article_headline()
{
  $post_id = get_the_ID();
  $byline_terms = wp_get_post_terms($post_id, 'byline');
  $series_term = get_post_type($post_id) === 'specials' ? pods('specials', $post_id)->field('specials_series') : wp_get_post_terms($post_id, 'serial')[0];
  $series_name = '';
  $series_slug = '';

  if ($series_term) {
    $series_name = get_post_type($post_id) === 'specials' ? $series_term['name'] : $series_term->name;
    $series_slug = get_post_type($post_id) === 'specials' ? $series_term['slug'] : $series_term->slug;
  }

  $location_terms = wp_get_post_terms($post_id, 'location');
  $avatar = null;

  if (!empty($byline_terms)) {
    $avatar = get_term_meta($byline_terms[0]->term_id, 'cover_image_url', true);
  }
?>
  <div class="container in-column gap--16 article-headline">
    <h1><?php the_title(); ?></h1>
    <div class="container in-column full-width single-article-meta">
      <div class="about-author gap--16">
        <div class="author-avatar">
          <?php if ($avatar) {
            echo '<img src="' . $avatar . '" alt="cover image" style="max-width:48px;">';
          } else {
            echo '<span class="meta-author-circle"></span>';
          } ?>
        </div>
        <div class="extra-info">
          <span class="bylines"><?php echo get_the_term_list($post_id, 'byline', '', ', ', ''); ?></span>
          <div class="post-meta">
            <span class="date"><?php echo get_the_time('j M Y'); ?></span>
            <?php if (!empty($location_terms)) {
              echo '<span class="taxonomy">';
              echo '<a href="' . home_url() . '/location/' . $location_terms[0]->slug . '">' . $location_terms[0]->name . '</a>';
              echo '</span>';
            } ?>
            <?php if (!empty($series_term)) {
              echo '<span class="taxonomy">';
              echo '<a href="' . home_url() . '/series/' . $series_slug . '">' . $series_name . '</a>';
              echo '</span>';
            } ?>
          </div>
        </div>
      </div>
      <div class="container social gap--4">
        <?php
        echo '<a href="#spotim-specific" class="theme--button simple secondary">';
        _e('Comments', 'mongabay');
        echo '</a>';
        ?>

        <?php social_share(); ?>
      </div>

    </div>
  </div>
<?php } ?>
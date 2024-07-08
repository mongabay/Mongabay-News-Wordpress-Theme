<?php
function specials_latest(array $series_array)
{
  echo '<div class="latest-series full-width in-column gap--40"><h1>';
  _e('Latest Specials', 'mongabay');
  echo '</h1>';

  $counter = 0;

  if (wp_is_mobile()) {
    $series_array = array_slice($series_array, 0, 3);
  }

  foreach ($series_array as $name) {
    $args = array(
      'post_type' => 'specials',
      'posts_per_page' => 1,
      'cache_results' => true,
      'tax_query' => array(
        array(
          'taxonomy' => 'serial',
          'field' => 'slug',
          'terms' => $name,
        ),
      )
    );

    $counter++;
    $query = new WP_Query($args);

    if ($query->have_posts()) {
      if ($counter === 1) {
        echo '<div class="grid--2 gap--40">';
      }

      if ($counter === 3) {
        echo '</div><div class="grid--3 gap--40">';
      }

      if ($counter === 6) {
        echo '<div class="grid--3 gap--40">';
      }

      while ($query->have_posts()) {
        $query->the_post();
        $tax_obj = get_term_by('slug', $name, 'serial');
        $tax_name = $tax_obj->name;
        $tax_url = home_url() . '/series/' . $name;
        $stories_count = $tax_obj->count;
?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image full-height">
              <div class="img-overlay"></div>
              <?php the_post_thumbnail('large'); ?>
              <div class="article--container-headline">
                <div class="title headline gap--8 left">
                  <h1><?php echo $tax_name; ?></h1>
                  <div class="meta">
                    <span class="count"><?php echo $stories_count; ?> stories</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
        <?php
        if ($counter === 5) {
          echo '</div>';
        }

        if ($counter === 5 || (wp_is_mobile() && $counter === 2)) {
          echo '<div class="container ';
          echo !wp_is_mobile() ? 'pv--56' : '';
          echo ' full-width">';
          banner('', 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large');
          echo '</div>';
        }

        if ($counter === 8 || (wp_is_mobile() && $counter === 3)) {
          echo '</div>';
        }
        ?>
<?php }
      wp_reset_postdata();
    }
  }
  echo "</div>";
}
?>
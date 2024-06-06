<?php
function formats_slider(array $post_formats, string $headline, array $terms_array = [], string $extra_class = null)
{ ?>
  <div id="formats-slider" class="section--highlight slider full-width">
    <div class="container in-column gap--40 pv--40">
      <h1 class="extra-large <?php echo $extra_class ? $extra_class : ''; ?>"><?php _e($headline, 'mongabay'); ?></h1>
    </div>
    <div class="slider-formats">
      <?php

      if (count($terms_array) > 0) {
        foreach ($terms_array as $name) {
          $args = array(
            'post_type' => $post_formats,
            'posts_per_page' => 1,
            'cache_results' => true,
            'tax_query' => array(
              array(
                'taxonomy' => 'serial',
                'field' => 'slug',
                'terms' => $name,
              ),
            ),
            'meta_query' => array(
              array(
                'key' => 'featured_as',
                'value' => 'featured',
                'compare' => '='
              )
            )
          );

          $query = new WP_Query($args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $tax_obj = get_term_by('slug', $name, 'serial');
              $tax_name = $tax_obj->name;
              $tax_url = home_url() . '/series/' . $name;
      ?>

              <div class="article--container">
                <div class="featured-image">
                  <a href="<?php echo $tax_url; ?>">
                    <?php the_post_thumbnail('large'); ?>
                    <div class="article--container-headline">
                      <div class="title headline gap--8 text-center">
                        <h1><?php echo $tax_name; ?></h1>
                        <div class="meta">
                          <span class="count"><?php echo $query->found_posts; ?> stories</span>
                        </div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            <?php }
          }
        }
      } else {
        $args = array(
          'post_type' => $post_formats,
          'posts_per_page' => 4,
          'cache_results' => true,
          'meta_query' => array(
            array(
              'key' => 'featured_as',
              'value' => 'featured',
              'compare' => '='
            )
          )
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
          while ($query->have_posts()) {
            $query->the_post();
            ?>

            <div class="article--container">
              <div class="featured-image">
                <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail('large'); ?>
                  <div class="article--container-headline">
                    <div class="title headline gap--8 text-center">
                      <h1><?php the_title() ?></h1>
                      <div class="meta">
                        <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                        <span class="date"><?php the_time('j F Y'); ?></span>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          <?php }
        }
      }
      echo '</div></div>';
    }

    function series_latest(array $series_array)
    {
      echo '<div class="full-width in-column gap--40"><h1>Latest sereies</h1>';

      $counter = 0;

      foreach ($series_array as $name) {
        $args = array(
          'post_type' => 'post',
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
          ?>
            <div class="article--container">
              <div class="featured-image">
                <a href="<?php echo $tax_url; ?>">
                  <?php the_post_thumbnail('large'); ?>
                  <div class="article--container-headline">
                    <div class="title headline gap--8 text-center">
                      <h1><?php echo $tax_name; ?></h1>
                      <div class="meta">
                        <span class="count"><?php echo $query->found_posts; ?> stories</span>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <?php
            if ($counter === 5) {
              echo '</div>';
            }

            if ($counter === 5) {
              echo '<div class="container pv--56 full-width">';
              banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large');
              echo '</div>';
            }

            if ($counter === 8) {
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
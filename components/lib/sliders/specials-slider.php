<?php
function specials_slider(string $headline, array $terms_array = [], string $extra_class = null)
{ ?>
  <div id="formats-slider" class="section--highlight slider full-width">
    <div class="container in-column gap--40 pv--40 ph--40">
      <h1 class="extra-large <?php echo wp_is_mobile() ? 'ph--20' : ''; ?> <?php echo $extra_class ? $extra_class : ''; ?>"><?php _e($headline, 'mongabay'); ?></h1>
    </div>
    <div class="slider-formats">
      <?php

      if (count($terms_array) > 0) {
        foreach ($terms_array as $name) {
          $args = array(
            'post_type' => 'specials',
            'posts_per_page' => 1,
            'order_by' => 'modified',
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
              $tax_obj = get_term_by('slug', $name, 'serial');
              $tax_name = $tax_obj->name;
              // $tax_url = home_url() . '/series/' . $name;
              $post_url = get_permalink();
              $series_posts = $tax_obj->count;
      ?>
              <div class="article--container full-height">
                <a href="<?php the_permalink(); ?>">
                  <div class="featured-image full-height">
                    <div class="img-overlay"></div>
                    <?php echo get_icon(get_the_ID()); ?>
                    <?php the_post_thumbnail('medium'); ?>
                    <div class="article--container-headline">
                      <div class="title headline gap--8 left">
                        <h1><?php echo $tax_name; ?></h1>
                        <div class="meta">
                          <span class="count"><?php echo $series_posts; ?> stories</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            <?php }
          } else {
            _e('No posts found', 'mongabay');
          }
        }
      } else {
        $args = array(
          'post_type' => 'specials',
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
                <div class="img-overlay"></div>
                <a href="<?php the_permalink(); ?>">
                  <?php echo get_icon(get_the_ID()); ?>
                  <?php the_post_thumbnail('medium'); ?>
                  <div class="article--container-headline">
                    <div class="title headline gap--8 text-center">
                      <h1><?php the_title() ?></h1>
                      <div class="meta">
                        <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                        <span class="date"><?php the_time('j M Y'); ?></span>
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
    ?>
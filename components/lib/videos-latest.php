<?php
function videos_latest()
{
  $args = array(
    'post_type' => 'videos',
    'posts_per_page' => 5,
    'cache_results' => true,
  );

  $counter = 0;
  $query = new WP_Query($args);

  function render_item($is_featured)
  { ?>
    <div class="article--container">
      <a href="<?php the_permalink(); ?>">
        <div class="featured-image">
          <?php the_post_thumbnail('large'); ?>
          <div class="article--container-headline">
            <div class="title headline gap--8 text-center">
              <h1><?php the_title(); ?></h1>
              <?php if ($is_featured) { ?>
                <div class="meta">
                  <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                  <span class="date"><?php the_time('j F Y'); ?></span>
                </div>
              <?php } ?>
            </div>
            <?php if (!$is_featured) { ?>
              <div class="meta">
                <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                <span class="date"><?php the_time('j F Y'); ?></span>
              </div>
            <?php } ?>
          </div>
        </div>
      </a>
    </div>
  <? }

  if ($query->have_posts()) { ?>
    <div class="container gap--20 grid--2">
      <?php

      $first_column = '';
      $second_column = '';
      $first_grid = '';
      $second_grid = '';

      while ($query->have_posts()) {
        $query->the_post();
        $counter++;

        if ($counter === 1) {
          $first_column .= '<div class="container in-column gap--40">';
          $first_column .= '
          <div class="article--container">
          <a href="' . get_the_permalink() . '">
            <div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '
              <div class="article--container-headline">
                <div class="title headline gap--8 text-center">
                  <h1>' . get_the_title() . '</h1>
                  
                    <div class="meta">
                      <span class="byline">' . getPostBylines(get_the_ID()) . '</span>
                      <span class="date">' . get_the_time('j F Y') . '</span>
                    </div>
                </div>

              </div>
            </div>
          </a>
        </div>
          ';
        }

        if ($counter === 2) {
          $first_grid .= '<div class="grid--2 gap--20">';
          $first_grid .= '
          <div class="article--container">
            <a href="' . get_the_permalink() . '">
              <div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>
              <div class="title headline gap--8">
                <h3>' . get_the_title() . '</h3>
              </div>
              <div class="meta">
                <span class="byline">' . getPostBylines(get_the_ID()) . '</span>
                <span class="date">' . get_the_time('j F Y') . '</span>
              </div>
            </a>
          </div>';
        }

        if ($counter === 3) {
          $first_grid .= '
          <div class="article--container">
            <a href="' . get_the_permalink() . '">
              <div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>
              <div class="title headline gap--8">
                <h3>' . get_the_title() . '</h3>
              </div>
              <div class="meta">
                <span class="byline">' . getPostBylines(get_the_ID()) . '</span>
                <span class="date">' . get_the_time('j F Y') . '</span>
              </div>
            </a>
          </div>';
          $first_column .= $first_grid;
          //close first grid and first column
          $first_column .= '</div></div>';
        }

        if ($counter === 4) {
          $second_column .= '<div class="container in-column gap--40">';
          $second_grid .= '<div class="grid--2 gap--20">';
          $second_grid .= '
          <div class="article--container">
            <a href="' . get_the_permalink() . '">
              <div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>
              <div class="title headline gap--8">
                <h3>' . get_the_title() . '</h3>
              </div>
              <div class="meta">
                <span class="byline">' . getPostBylines(get_the_ID()) . '</span>
                <span class="date">' . get_the_time('j F Y') . '</span>
              </div>
            </a>
          </div>';
        }

        if ($counter === 5) {
          $second_grid .= '
          <div class="banner gap--20 accent pv--20">
          <div class="inner">
            <div class="title">
              <h1>We are nonprofit</h1>
            </div>
              <div class="copy">Help us tell stories of biodiversity loss, climate change and more.</div>
            <a href="" class="theme--button primary full-width">Donate<span class="icon icon-right"></span>
            </a>
          </div>
        </div>
          ';
          //close second grid
          $second_grid .= '</div>';
          $second_column .= $second_grid;
          $second_column .= '
          <div class="article--container">
            <a href="' . get_the_permalink() . '">
              <div class="featured-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '
                <div class="article--container-headline">
                  <div class="title headline gap--8 text-center">
                    <h1>' . get_the_title() . '</h1>
                    <div class="meta">
                      <span class="byline">' . getPostBylines(get_the_ID()) . '</span>
                      <span class="date">' . get_the_time('j F Y') . '</span>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>';
          //close second column
          $second_column .= '</div>';
        }
      }

      echo $first_column;
      echo $second_column;
      echo '</div>';
      ?>
  <?php }
  wp_reset_postdata();
}
  ?>
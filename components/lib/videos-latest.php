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

  function get_article_card($id, $is_large = false)
  {
    $headline = '
    <div class="title headline gap--8">
      <h3>' . get_the_title() . '</h3>
    </div>
    <div class="post-meta">
      <span class="byline">' . getPostBylines($id) . '</span>
      <span class="date">' . get_the_time('j M Y') . '</span>
    </div>
    ';
    $extra_class = !wp_is_mobile() ? 'text-center' : '';
    $headline_large = '
    <div class="article--container-headline">
      <div class="title headline gap--8 ' . $extra_class . '">
        <h1>' . get_the_title() . '</h1>
          <div class="meta">
            <span class="byline">' . getPostBylines($id) . '</span>
            <span class="date">' . get_the_time('j M Y') . '</span>
          </div>
      </div>
    </div>';

    return '
      <div class="article--container">
        <a href="' . get_the_permalink($id) . '">
          <div class="featured-image">'
      . ($is_large ? '<div class="img-overlay"></div>' : '')
      . get_icon($id)
      . get_the_post_thumbnail($id, 'medium')
      . ($is_large ? $headline_large : '')
      . '</div>'
      . (!$is_large ? $headline : '')
      . '</a></div>';
  };

  if ($query->have_posts()) { ?>
    <div class="container gap--20 grid--2">
      <?php

      $first_column = '';
      $second_column = '';
      $first_grid = '';
      $second_grid = '';
      $banner_nonprofit = '
      <div class="banner gap--20 accent ph--20 pv--20">
        <div class="inner">
          <div class="title">
            <h1 class="lh--tight">We’re a nonprofit</h1>
          </div>
            <div class="copy">Help us tell impactful stories of biodiversity loss, climate change, and more</div>
          <a href="'.get_donate_link().'" class="theme--button primary full-width">Donate<span class="icon icon-right"></span>
          </a>
        </div>
      </div>';
      $banner_newsletter = '
      <div class="banner gap--20 accent ph--20 pv--20">
        <div class="inner">
          <div class="title">
            <h1>Subscribe</h1>
          </div>
            <div class="copy">Stay informed with news and inspiration from nature’s frontline.</div>
          <a href="'.get_subscribe_link().'" class="theme--button primary full-width">Newsletter<span class="icon icon-right"></span>
          </a>
        </div>
      </div>
      ';

      while ($query->have_posts()) {
        $query->the_post();
        $counter++;

        if ($counter === 1) {
          $first_column .= '<div class="container in-column gap--40">';
          $first_column .= get_article_card(get_the_ID(), true);
        }

        if ($counter === 2) {
          $first_grid .= '<div class="grid--2 gap--20">';
          $first_grid .= get_article_card(get_the_ID(), false);
        }

        if ($counter === 3) {
          $first_grid .= get_article_card(get_the_ID(), false);
          $first_column .= $first_grid;
          //close first grid and first column
          $first_column .= '</div></div>';
        }

        if ($counter === 4) {
          $second_column .= '<div class="container in-column gap--40">';
          $grid_class = !wp_is_mobile() ? 'grid--2' : 'container';
          $second_grid .= '<div class="' . $grid_class . ' gap--20">';
          $second_grid .= !wp_is_mobile() ? get_article_card(get_the_ID(), false) : $banner_newsletter;
          if (wp_is_mobile()) {
            ///close second grid on mobile
            $second_grid .= '</div>';
            $second_grid .= '<div class="grid--2 gap--20">' . get_article_card(get_the_ID(), false);
          }
        }

        if ($counter === 5) {
          $second_grid .= !wp_is_mobile() ? $banner_nonprofit : get_article_card(get_the_ID(), false);
          //close second grid
          $second_grid .= '</div>';
          $second_column .= $second_grid;
          $second_column .= !wp_is_mobile() ? get_article_card(get_the_ID(), !wp_is_mobile()) : '';
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
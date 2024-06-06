<?php
function inspiration_banner()
{
  $post_types = array('videos', 'podcasts', 'specials');

  $section = '<div class="section--inspiration full-width"><div class="container"><div class="title column--40"><h1>News and Inspiration from Nature\'s Frontline.</h1></div><div class="items column--60">';
  $first_column = '<div class="column--60">';
  $second_column = '<div class="column--40">';

  $has_videos = false;
  $has_podcasts = false;
  $has_articles = false;

  foreach ($post_types as $type) {
    $args = array(
      'post_type' => $type,
      'posts_per_page' => 1,
      'cache_results' => true,
    );


    $query = new WP_Query($args);

    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();

        if ($type === 'videos') {
          $has_videos = true;
          $first_column .= '<div class="item-container first">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '<div class="item-title">Videos</div></div>';
        } elseif ($type === 'podcasts') {
          $has_podcasts = true;
          $first_column .= '<div class="item-container">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '<div class="item-title podcast">Podcasts</div></div>';
        } elseif ($type === 'specials') {
          $has_articles = true;
          $second_column .= '<div class="item-container second">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '<div class="item-title articles">Articles</div></div>';
        }
      };

      wp_reset_postdata();
    };
  }

  if ($has_videos || $has_podcasts || $has_articles) {
    $section .= $first_column . '</div>';
    $section .= $second_column . '</div>';
    $section .= '</div></div></div>';
    echo $section;
  }
}

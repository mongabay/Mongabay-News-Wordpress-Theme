<?php
function inspiration_banner()
{
  $post_types = array('videos', 'podcasts', 'post');
  $title = __("News and Inspiration from Nature's Frontline.", "mongabay");
  $section = '<div class="section--inspiration full-width"><div class="container ph--40 gap--40"><div class="title column--40"><h1>' . $title . '</h1></div><div class="column--60">';
  $first_row = '<div class="container in-row items">';
  $first_column = '<div class="column--60">';
  $second_column = '<div class="column--40">';
  $second_row = '<div class="container in-row items">';

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
          $first_column .= '<div class="item-container first"><a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</a><div class="item-title"><span class="icon-play"></span><a href="' . home_url() . '/videos">Videos</a></div></div>';
        } elseif ($type === 'podcasts') {
          $has_podcasts = true;
          $second_row .= '<div class="item-container third"><a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</a><div class="item-title podcast"><span class="icon-audio"></span><a href="' . home_url() . '/podcasts">Podcasts</a></div></div>';
        } elseif ($type === 'post') {
          $has_articles = true;
          $second_column .= '<div class="item-container second"><a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</a><div class="item-title articles"><span class="icon-articles"></span><a href="' . home_url() . '/articles">Articles</a></div></div>';
        }
      };

      wp_reset_postdata();
    };
  }

  if ($has_videos || $has_podcasts || $has_articles) {
    $first_row .= $first_column . '</div>';
    $first_row .= $second_column . '</div>';
    $section .= $first_row . '</div>';
    $section .= $second_row . '</div>';
    $section .= '</div></div></div>';
    echo $section;
  }

  // echo "<script>

  //   function mbHeroSmoothScroll() {
  //       const items = jQuery('.section--inspiration .item-container'),
  //           dY = 30;
  //       const ease = function (a, b, n) {
  //           return (1 - n) * a + n * b;
  //       };
  //       const inView = function (item, y) {
  //           if (window.scrollY + window.innerHeight > item.offset().top &&
  //               window.scrollY < item.offset().top + item.outerHeight()) {
  //               return true;
  //           }
  //           return false;
  //       };
  //       const itemsInView = function () {
  //           return items.filter(function () {
  //               return inView(jQuery(this));
  //           });
  //       };
  //       const init = function () {
  //           items.each(function () {
  //               var item = jQuery(this);
  //               item.data('y', 0);
  //               item.data('c', Math.random());
  //           });
  //           function loop() {
  //               itemsInView().each(function () {
  //                   var item = jQuery(this);
  //                   var deltaY = (item.offset().top - window.scrollY) / window.innerHeight - 1;
  //                   item.data('y', ease(item.data('y'), deltaY, item.data('c') * .15));
  //                   item.css({
  //                       'transform': 'translate3d(0,' + (dY * item.data('y')).toFixed(2) + '%,0)',
  //                   });
  //               });
  //               requestAnimationFrame(loop);
  //           }
  //           jQuery(window).on('scroll', loop);
  //       };
  //       return {
  //           init: function () {
  //               if (items.length) init();
  //           }
  //       };
  //   }

  //   mbHeroSmoothScroll().init();
  // </script>";
}

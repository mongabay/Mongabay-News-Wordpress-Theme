<?php
function featured_articles_slider($posts_per_page, $offset)
{
  $args = array(
    'post_type' => array('post', 'videos', 'podcasts', 'specials'),
    'posts_per_page' => $posts_per_page,
    'cache_results' => true,
    'offset' => $offset,
    'meta_query' => array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    ),
  );

  $query = new WP_Query($args); ?>

  <div class="section--highlight slider full-width">
    <div class="container in-column ph--40 pv--40 gap--40">
      <h1 class="text-center">The outstanding <span>feature stories</span> give one step forward</h1>
    </div>
    <div class="in-column gap--20">
      <div id="series-slider">
        <div class="article--container slider slider-featured">
          <?php while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="article--container">
              <a href="<?php the_permalink(); ?>">
                <div class="featured-image">
                  <?php the_post_thumbnail('large'); ?>
                  <div class="article--container-headline">
                    <div class="title headline gap--8 text-center">
                      <h5>Feature story</h5>
                      <h1><?php the_title(); ?></h1>
                      <div class="meta">
                        <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                        <span class="date"><?php the_time('j M Y'); ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>
<?php
function topics_section(string $headline, array $topics, array $extra_params = [])
{
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 1,
    'cache_results' => true,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => 'topic',
        'field'    => 'slug',
        'terms'    => array('climate'),
      ),
      array(
        'taxonomy' => 'topic',
        'field'    => 'slug',
        'terms'    => array('oceans'),
      ),
    ),
  );

  $link_copy = 'all topics';
  $link_url = '';

  if (count($extra_params) > 0) {
    $link_copy = $extra_params['link_copy'];
    $link_url = $extra_params['link_url'];
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post(); ?>
      <div class="section--highlight full-width pv--80">
        <div class="container">
          <div class="title column--50 align-left">
            <h1>
              <?php _e($headline, 'mongabay'); ?>
              <?php
              if (count($topics) > 0) {
                foreach ($topics as $topic) {
                  echo '<span class="outlined">' . $topic . '</span>';
                }
              }
              ?>
              <span class="outlined"><a href="<?php echo $link_url; ?>"><?php echo $link_copy; ?><span class="icon icon-right"></span></a></span>
            </h1>
          </div>
          <div class="column--50">
            <div class="article--container">
              <a href="<?php the_permalink(); ?>">
                <div class="featured-image">
                  <?php the_post_thumbnail('medium'); ?>
                </div>
                <div class="title headline">
                  <h2><?php the_title(); ?></h2>
                </div>
                <div class="meta pv--8">
                  <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                  <span class="date"><?php the_time('j F Y'); ?></span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
<?php }

    wp_reset_postdata();
  }
};
?>
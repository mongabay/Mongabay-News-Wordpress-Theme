<?php
function articles_listing($posts_per_page, $offset, $show_all_thumbnails, $thumbnail_size, $taxonomy)
{
  $args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_per_page,
    'offset' => $offset,
    'cache_results' => true,
  );

  if ($taxonomy) {
    $tax_query = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );

    $args['taxonomy'] = $tax_query;
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
?>
      <div class="article--container ph--8">
        <a href="<?php the_permalink(); ?>">
          <?php if (($show_all_thumbnails || $post_counter == 1) && has_post_thumbnail()) { ?>
            <div class="featured-image">
              <?php the_post_thumbnail($thumbnail_size) ?>
            </div>
          <?php }; ?>
          <div class="title headline">
            <h3><?php the_title(); ?></h3>
          </div>
          <div class="meta">
            <span class="byline"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'byline', '', ', ', '')); ?></span>
            <span class="date"><?php the_time('j F Y'); ?></span>
          </div>
        </a>
      </div>
    <?php }
    wp_reset_postdata();
  } else {
    esc_html_e('Sorry, no posts matched your criteria.');
  }
}

function featured_articles_listing($post_type, $posts_per_page, $offset, $thumbnail_size, $taxonomy)
{
  $args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => $post_type,
    'post_status' => 'publish',
    'offset' => $offset,
    'cache_results' => true,
    'meta_query' => array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    ),
  );


  if ($taxonomy) {
    $tax_query = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );

    $args['tax_query'] = $tax_query;
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    echo '<div class="article--container">';

    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
    ?>

      <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image">
              <?php the_post_thumbnail($thumbnail_size) ?>
              <div class="title headline">
                <h1><?php the_title(); ?></h1>
              </div>
            </div>
          </a>
        </div>
      <?php } else {
        if ($post_counter == 2) {
          echo '<div class="grid--2 gap--40 ph--40">';
        }; ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) { ?>
              <div class="featured-image">
                <?php the_post_thumbnail($thumbnail_size) ?>
              </div>
            <?php }; ?>
            <div class="title headline ph--16">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="meta">
              <span class="byline"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'byline', '', ', ', '')); ?></span>
              <span class="date"><?php the_time('j F Y'); ?></span>
            </div>
          </a>
        </div>
      <?php }; ?>
  <?php }
    echo '</div></div>';
    wp_reset_postdata();
  } else {
    esc_html_e('Sorry, no posts matched your criteria.');
  }
}

function inspiration_banner()
{ ?>
  <div class="section--inspiration full-width">
    <div class="container ph--40 pv--40">
      <div class="title column--40">
        <h1>News and Inspiration from Nature's Frontline.</h1>
      </div>
      <div class="items column--60">
        <div class="column--60">
          <div class="item-container first">
            <img src="./img/08.jpg" />
            <div class="item-title video">Videos</div>
          </div>
          <div class="item-container">
            <img src="./img/08.jpg" />
            <div class="item-title podcast">Podcasts</div>
          </div>
        </div>
        <div class="column--40">
          <div class="item-container second">
            <img src="./img/08.jpg" />
            <div class="item-title articles">Articles</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php }

function series_articles_listing()
{
  $series = (array('forest-trackers', 'oceans', 'amazon-conservation', 'land-rights-and-extractives', 'endangered-environmentalists', 'indonesias-forest-guardians', 'conservation-effectiveness', 'southeast-asian-infrastructure'));

  foreach ($series as $name) {
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => 3,
      'cache_results' => true,
      // TODO change number to 4
      // 'tax_query' => array(
      //   array(
      //     'taxonomy' => 'serial',
      //     'field'    => 'slug',
      //     'terms'    => $name,
      //   ),
      // ),
    );

    $query = new WP_Query($args);

    if ($name == 'forest-trackers') { ?>
      <div class="section--highlight series slider full-width">
        <div class="container in-column gap--40 ph--40 pv--40">
          <h1>Mongabay <span>series</span> gather stories with a lot in common.</h1>
          <div class="in-column gap--20">
            <h3><?php _e('Forest tracker', 'mongabay'); ?></h3>
            <div id="series-slider">
              <div class="article--container slider">
                <?php while ($query->have_posts()) {
                  $query->the_post(); ?>
                  <div class="article--container">
                    <a href="<?php the_permalink(); ?>">
                      <div class="featured-image">
                        <?php the_post_thumbnail('large'); ?>
                        <div class="article--container-headline">
                          <div class="title headline gap--8 text-center">
                            <h1><?php the_title(); ?></h1>
                            <div class="meta">
                              <span class="byline"><?php echo strip_tags(get_the_term_list(get_the_ID(), 'byline', '', ', ', '')); ?></span>
                              <span class="date"><?php the_time('j F Y'); ?></span>
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
      </div>
  <?php } else {
      $args['posts_per_page'] = 1;
    }
  }

  ?>

<?php };

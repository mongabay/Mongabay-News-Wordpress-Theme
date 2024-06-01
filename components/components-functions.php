<?php
function getPostBylines($post_id)
{
  return strip_tags(get_the_term_list($post_id, 'byline', '', ', ', ''));
}

function banner($link, $title, $copy, $button_copy, $extra_class = '')
{ ?>
  <div class="banner gap--20 <?php echo strlen($extra_class) > 0 ? ' ' . $extra_class : ''; ?>">
    <div class="title">
      <h1><?php _e($title, 'momgabay'); ?></h1>
    </div>
    <?php if (strlen($copy) > 0) { ?>
      <div class="copy">
        <?php _e($copy, 'mongabay'); ?>
      </div>
    <?php } ?>
    <a href="<?php echo $link; ?>" class="theme--button primary">
      <?php _e($button_copy, 'mongabay'); ?><span class="icon icon-right"></span>
    </a>
  </div>
  <?php }

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
      <div class="article--container pv--8">
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
            <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
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

function featured_articles_listing(
  string $post_type,
  int $posts_per_page,
  int $offset,
  string $thumbnail_size,
  int $items_in_row,
  ?bool $show_featured,
  ?string $taxonomy
) {
  $args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => $post_type,
    'post_status' => 'publish',
    'offset' => $offset,
    'cache_results' => true,
  );

  if ($show_featured == true) {
    $args['meta_query'] = array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    );
  }

  if ($taxonomy) {
    $$args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
      )
    );
  }

  $query = new WP_Query($args);
  $post_counter = 0;

  if ($query->have_posts()) {
    echo '<div class="article--container full-width">';

    while ($query->have_posts()) {
      $query->the_post();
      $post_counter++;
    ?>

      <?php if ($post_counter == 1 && has_post_thumbnail()) { ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <div class="featured-image">
              <?php the_post_thumbnail($thumbnail_size) ?>
              <div class="title headline <?php echo $post_type !== 'post' ? 'text-center' : ''; ?>">
                <h1><?php the_title(); ?></h1>
                <?php if ($post_type !== 'post') { ?>
                  <div class="meta">
                    <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                    <span class="date"><?php the_time('j F Y'); ?></span>
                  </div>
                <?php } ?>
              </div>
            </div>
          </a>
        </div>
      <?php } else {
        if ($post_counter == 2) {
          echo '<div class="grid--' . ($items_in_row) . ' gap--40 pv--40">';
        }; ?>
        <div class="article--container">
          <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()) { ?>
              <div class="featured-image">
                <?php the_post_thumbnail($thumbnail_size) ?>
              </div>
            <?php }; ?>
            <div class="title headline pv--16">
              <h3><?php the_title(); ?></h3>
            </div>
            <div class="meta">
              <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
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
    <div class="container">
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
  $series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives'));
  // TODO decide which series are in the list and how to manage a list with more than 3
  // $series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives', 'endangered-environmentalists', 'indonesias-forest-guardians', 'conservation-effectiveness', 'southeast-asian-infrastructure'));


  $argsTracking = array(
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

  $queryTracking = new WP_Query($argsTracking); ?>

  <div class="section--highlight slider full-width">
    <div class="container in-column gap--40 pv--40">
      <h1>Mongabay <span>series</span> gather stories with a lot in common.</h1>
      <div class="in-column gap--20">
        <h3><?php _e('Forest tracker', 'mongabay'); ?></h3>
        <div id="series-slider">
          <div class="article--container slider slider-series">
            <?php while ($queryTracking->have_posts()) {
              $queryTracking->the_post(); ?>
              <div class="article--container">
                <a href="<?php the_permalink(); ?>">
                  <div class="featured-image">
                    <?php the_post_thumbnail('large'); ?>
                    <div class="article--container-headline">
                      <div class="title headline gap--8 text-center">
                        <h1><?php the_title(); ?></h1>
                        <div class="meta">
                          <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
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
        <div id="series--description-container" class="gap--20">
          <div class="series--description column--60">
            <?php
            $serial_obj = get_term_by('slug', 'forest-trackers', 'serial');

            if ($serial_obj) {
              echo '<p>' . $serial_obj->description . '</p>';
            }
            ?>

          </div>
          <div class="series--browse column--40">
            <a href="<?php echo home_url() . '/series/' . $name; ?>" class="theme--button outlined no-margins">Forest tracker series <span class="icon icon-right"></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="section--series-more full-width">
    <div class="container in-column ph--40 pv--40 gap--16">
      <div class="section-title">
        <h4><?php _e('More series', 'mongabay'); ?></h4>
        <div class="divider"></div>
      </div>
      <div class="grid--3 gap--40">
        <?php
        foreach ($series as $name) {
          $argsSeries = array(
            'post_type' => 'post',
            'posts_per_page' => 1,
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

          $querySeries = new WP_Query($argsSeries);

          while ($querySeries->have_posts()) {
            $querySeries->the_post();
            $serial_obj = get_term_by('slug', $name, 'serial');
            $serial_name = $serial_obj->name; ?>

            <div class="article--container">
              <div class="featured-image">
                <?php the_post_thumbnail('medium'); ?>
                <div class="article--container-headline">
                  <div class="title headline gap--8">
                    <div class="meta">
                      <span class="count"><?php echo $querySeries->found_posts; ?> stories</span>
                    </div>
                    <h3><?php echo $serial_name; ?></h3>
                  </div>
                </div>
              </div>
            </div>
        <?php }
        };
        ?>
      </div>
    </div>
  </div>
<?php
}

function featured_articles_slider($posts_per_page, $offset)
{
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => $posts_per_page,
    'cache_results' => true,
    'offset' => $offset,
    'meta_query' => array(
      array(
        'key' => 'featured_as',
        'value' => 'featured',
        'compare' => '='
      )
    )
  );

  $query = new WP_Query($args); ?>

  <div class="section--highlight slider full-width">
    <div class="container in-column pv--40 gap--40">
      <h1 class="text-center">The outstanding <span>feature stories</span> give one step forward</h1>
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
  <?php
}

function podcastsBanner()
{
  $args = array(
    'post_type' => 'podcasts',
    'posts_per_page' => 1,
    'cache_results' => true,
  );

  $query = new WP_Query($args);

  while ($query->have_posts()) {
    $query->the_post(); ?>
    <div class="container in-column gap--40">
      <h1>Uncover a world of thought provoking <span>podcasts</span></h1>
      <div class="in-column gap--20">

        <div class="full-width sound-wave">
          <div class="article--container narrow">
            <?php if (has_post_thumbnail()) { ?>
              <div class="featured-image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
              </div>
            <?php }; ?>
          </div>
        </div>
        <div class="article--container">
          <div class="title headline gap--8">
            <h3 class="text-center">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <div class="meta text-center">
              <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
              <span class="date"><?php the_time('j F Y'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php }
}

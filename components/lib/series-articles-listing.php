<?php
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

  $queryTracking = new WP_Query($argsTracking);

  if ($queryTracking->have_posts()) { ?>
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
  <?php } ?>
  <?php
  $post_counter = 0;

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

    if ($querySeries->have_posts()) {
      $post_counter++;

      if ($post_counter == 1) { ?>
        <div class="section--series-more full-width">
          <div class="container in-column pv--40 gap--16">
            <div class="section-title">
              <h4><?php _e('More series', 'mongabay'); ?></h4>
              <div class="divider"></div>
            </div>
            <div class="grid--3 gap--40">
            <?php }
            ?>

            <?php
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
              <?php if ($post_counter === sizeof($series)) { ?>
            </div>
          </div>
        </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
<?php } ?>
<?php }
?>
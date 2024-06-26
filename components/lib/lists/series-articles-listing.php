<?php
function series_articles_listing(bool $show_headline)
{
  // TODO decide which series are in the list and how to manage a list with more than 3
  $series = (array('oceans', 'amazon-conservation', 'land-rights-and-extractives'));

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 1,
    'tax_query' => array(
      array(
        'taxonomy' => 'serial', // Taxonomy name
        'field' => 'slug',
        'terms' => array(), // Empty array to fetch posts with any term
        'operator' => 'EXISTS', // Operator to fetch posts with any term
      ),
    ),
    'cache_results' => true,
  );

  $single_serie_slug = null;
  $single_serie_name = null;
  $single_serie_description = null;
  $updatedSeriesQuery = new WP_Query($args);

  if ($updatedSeriesQuery->have_posts()) {
    while ($updatedSeriesQuery->have_posts()) {
      $updatedSeriesQuery->the_post();
      $single_serie_slug = get_the_terms(get_the_ID(), 'serial')[0]->slug;
      $single_serie_name = get_the_terms(get_the_ID(), 'serial')[0]->name;
      $single_serie_description = get_the_terms(get_the_ID(), 'serial')[0]->description;
    }
  }

  if ($single_serie_slug) {
    $serie_args = array(
      'post_type' => 'post',
      'posts_per_page' => 4,
      'cache_results' => true,
      'tax_query' => array(
        array(
          'taxonomy' => 'serial',
          'field'    => 'slug',
          'terms'    => $single_serie_slug,
        ),
      ),
    );

    $serieQuery = new WP_Query($serie_args);

    if ($serieQuery->have_posts()) { ?>
      <div class="section--highlight slider full-width">
        <div class="container full-width in-column gap--40 pv--40">
          <?php if ($show_headline) { ?>
            <h1><span class="icon icon-series">Special issues</span> connect the dots between stories</h1>
          <?php } else { ?>
            <h1><?php echo $single_serie_name; ?></h1>
          <?php } ?>

          <div class="in-column gap--20">
            <?php if ($show_headline) { ?>
              <h3><?php echo $single_serie_name; ?></h3>
            <?php } ?>
            <div id="series-slider">
              <div class="article--container slider slider-series">
                <?php $count = 0;
                while ($serieQuery->have_posts()) {
                  $serieQuery->the_post(); ?>
                  <div class="article--slide <?php echo ($count == 0) ? 'expanded' : 'collapsed'; ?>">
                    <a href="<?php the_permalink(); ?>">
                      <div class="featured-image">
                        <?php the_post_thumbnail('large'); ?>
                        <div class="article--container-headline">
                          <div class="title headline gap--8 text-center">
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
                <?php $count++;
                }
                wp_reset_postdata(); ?>
              </div>
            </div>
            <div id="series--description-container" class="gap--20">
              <div class="series--description column--60">
                <?php
                if ($single_serie_description) {
                  echo '<p>' . $single_serie_description . '</p>';
                }
                ?>

              </div>
              <div class="series--browse column--40">
                <a href="<?php echo home_url() . '/series/' . $single_serie_slug; ?>" class="theme--button outlined no-margins"><?php echo $single_serie_name; ?> series <span class="icon icon-right"></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        let articles = document.querySelectorAll(".article--slide");
        let currentIndex = 0;
        let autoSlideInterval;
        const slideIntervalTime = 5000;

        function showNextArticle(nextIndex) {
          articles[currentIndex].classList.remove("expanded");
          articles[currentIndex].classList.add("collapsed");

          currentIndex = nextIndex;

          articles[currentIndex].classList.remove("collapsed");
          articles[currentIndex].classList.add("expanded");
        }

        function startAutoSlide() {
          autoSlideInterval = setInterval(() => {
            showNextArticle((currentIndex + 1) % articles.length);
          }, slideIntervalTime);
        }

        function stopAutoSlide() {
          clearInterval(autoSlideInterval);
        }

        articles.forEach((article, index) => {
          article.addEventListener("click", (event) => {
            if (!article.classList.contains("expanded")) {
              showNextArticle(index);
              event.preventDefault();
            } else {
              window.location.href = article.querySelector("a").href;
            }
          });

          article.addEventListener("mouseenter", stopAutoSlide);
          article.addEventListener("mouseleave", startAutoSlide);
        });

        articles[currentIndex].classList.add("expanded");

        startAutoSlide();
      });
    </script>
    <?php }

  if (!$show_headline) {
    return;
  }

  $post_counter = 0;

  foreach ($series as $name) {
    $argsSeries = array(
      'post_type' => 'post',
      'posts_per_page' => 1,
      'cache_results' => true,
      'tax_query' => array(
        array(
          'taxonomy' => 'serial',
          'field'    => 'slug',
          'terms'    => $name,
        ),
      ),
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
              $serial_name = $serial_obj->name;
            ?>

              <div class="article--container full-height">
                <a href="<?php echo home_url() . '/series/' . $name; ?>">
                  <div class="featured-image full-height">
                    <?php the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'full-height']); ?>
                    <div class="article--container-headline">
                      <div class="title headline gap--8">
                        <div class="meta">
                          <span class="count"><?php echo $querySeries->found_posts; ?> stories</span>
                        </div>
                        <h3><?php echo $serial_name; ?></h3>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
              <?php if ($post_counter === sizeof($series)) { ?>
            </div>
          </div>
        </div>
<?php
                wp_reset_postdata();
              }
            }
          }
        }
      }
?>
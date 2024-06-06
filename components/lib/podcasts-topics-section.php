<?php
function podcasts_topics_section(string $headline, array $topics, array $extra_params): void
{
  $tax_args = array();

  foreach ($topics as $topic) {
    $tax_args['terms'][] = array(
      'taxonomy' => 'topic',
      'field' => 'slug',
      'terms' => $topic,
    );
  }

  $args = array(
    'post_type' => 'podcasts',
    'posts_per_page' => count($topics),
    'cache_results' => true,
    // 'tax_query' => array(
    //   'relation' => 'OR',
    //   $tax_args,
    // ),
  );

  $link_copy = 'all topics';
  $link_url = '';

  if (count($extra_params) > 0) {
    $link_copy = $extra_params['link_copy'];
    $link_url = $extra_params['link_url'];
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) { ?>
    <div class="section--highlight full-width pv--80 gap--80 in-column">
      <div class="container in-column gap--80">
        <div class="title column--100 align-left">
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
      </div>


      <?php
      $counter = 0;

      while ($query->have_posts()) {
        $counter++;
        $query->the_post();
        $terms = get_the_terms(get_the_ID(), 'topic');

        $filtered_terms = array_filter($terms, function ($term) use ($topics) {
          return in_array($term->slug, $topics);
        });

        $sorted_terms = array_values($filtered_terms);
        usort($sorted_terms, function ($a, $b) {
          return strcasecmp($a->slug, $b->slug);
        });

        foreach ($sorted_terms as $term) {
          // print_r($term->slug);
        }

        $first_term = reset($filtered_terms);
        $term_slug = $first_term ? $first_term->slug : '';
      ?>
        <div class="container in-row gap--20 topics-slide slide-<?php echo $term_slug; ?> <?php echo $counter === 1 ? 'active' : ''; ?>">
          <div class="column--20">
            <div class="featured-image">
              <?php the_post_thumbnail('medium'); ?>
            </div>
          </div>
          <div class="column--80 in-column gap--10">
            <div>
              <div class="title headline">
                <h2><?php the_title(); ?></h2>
              </div>
              <div class="meta pv--8">
                <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                <span class="date"><?php the_time('j F Y'); ?></span>
              </div>
            </div>
            <div class="podcast--player">
              <?php echo get_field('podcast_source'); ?>
            </div>
          </div>
        </div>
  <?php }
      wp_reset_postdata();
      echo '</div>';
    }
  };
  ?>
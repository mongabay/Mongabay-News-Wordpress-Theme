<?php
function podcasts_topics_section(string $headline, array $topics, array $extra_params): void
{
  $tax_args = array(
    'taxonomy' => 'topic',
    'field' => 'slug',
    'terms' => $topics,
  );

  $args = array(
    'post_type' => 'podcasts',
    'posts_per_page' => 1,
    'cache_results' => true,
    'tax_query' => array(
      'relation' => 'OR',
      $tax_args,
    ),
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
                $topic_name = get_term_by('slug', $topic, 'topic')->name;

                echo '<span class="outlined"><a href="' . home_url() . '/?s=&format=podcasts&topics=' . $topic . '">' . strtolower($topic_name) . '</a></span>';
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
      ?>
        <div class="container in-row gap--20 topics-slide">
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
              <div class="podcast-link">
                <a href="<?php the_permalink(); ?>" class=""><?php _e('Listen full podcast', 'mongabay'); ?></a>
              </div>
              <div class="podcast-embed">
                <?php
                global $wp_embed;
                // echo do_shortcode($wp_embed->autoembed(pods('podcasts', get_the_ID())->field('podcast_source')));
                echo do_shortcode('[iframe height="128" src="' . pods('podcasts', get_the_ID())->field('podcast_embed') . '"]');
                ?>
              </div>
            </div>
          </div>
        </div>
  <?php }
      wp_reset_postdata();
      echo '</div>';
    }
  };
  ?>
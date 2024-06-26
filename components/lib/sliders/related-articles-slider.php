<?php
function related_articles_slider(int $post_id)
{
  $post_format = array('post', 'videos', 'podcasts', 'custom-story', 'short-article');
  $taxonomies = array('serial', 'location', 'topic');
  $terms = wp_get_post_terms($post_id, $taxonomies);
  $args = array(
    'post_type' => $post_format,
    'post_status' => 'publish',
    'posts_per_page' => 8,
    'cache_results' => true,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => 'serial',
        'field' => 'term_id',
        'terms' => wp_list_pluck($terms, 'term_id'),
        'operator' => 'IN'
      ),
      array(
        'taxonomy' => 'location',
        'field' => 'term_id',
        'terms' => wp_list_pluck($terms, 'term_id'),
        'operator' => 'IN'
      ),
      array(
        'taxonomy' => 'byline',
        'field' => 'term_id',
        'terms' => wp_list_pluck($terms, 'term_id'),
        'operator' => 'IN'
      ),
      array(
        'taxonomy' => 'topic',
        'field' => 'term_id',
        'terms' => wp_list_pluck($terms, 'term_id'),
        'operator' => 'IN'
      )
    ),
    'post__not_in' => array($post_id)
  );

  $query = new WP_Query($args);
  $thumbnail_size = 'medium';

  if ($query->have_posts()) {
?>
    <div class="container in-column full-width">
      <div class="container section--highlight in-row pv--40 gap--40 space-between align-center">
        <h1 class="text-left"><?php _e('Related Articles', 'mongabay'); ?></h1>
        <div class="related--slider-nav">
          <button class="prev"><span class="icon icon-left-open"></span></button>
          <button class="next active"><span class="icon icon-right-open"></span></button>
          <div class="related--slider-dots">
            <span class="slider-dot first active"></span>
            <span class="slider-dot second"></span>
          </div>
        </div>
      </div>
      <div class="related--slider-container in-column gap--20">
        <div class="related--slider">
          <?php while ($query->have_posts()) {
            $query->the_post(); ?>
            <div class="article--container">
              <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) { ?>
                  <div class="featured-image">
                    <?php echo get_icon(get_the_ID()); ?>
                    <?php the_post_thumbnail($thumbnail_size) ?>
                  </div>
                <?php }; ?>
                <div class="title headline">
                  <h3><?php the_title(); ?></h3>
                </div>
                <div class="post-meta pv--8">
                  <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                  <span class="date"><?php the_time('j M Y'); ?></span>
                </div>
              </a>
            </div>
          <?php
          } ?>
        </div>
      </div>
    </div>
    <script>
      const sliderWidth = document.querySelector('.related--slider').scrollWidth;

      jQuery('.related--slider-nav button').on('click', function() {
        const direction = jQuery(this).hasClass('prev') ? -1 : 1;

        const currentScroll = jQuery('.related--slider').scrollLeft();
        const newScroll = currentScroll + (direction * sliderWidth / 2);

        jQuery('.related--slider-container').animate({
          scrollLeft: newScroll
        }, 500);

        if (newScroll < 0) {
          jQuery('.related--slider-nav button.prev').removeClass('active');
          jQuery('.related--slider-nav button.next').addClass('active');
          jQuery('.related--slider-nav .slider-dot.first').addClass('active');
          jQuery('.related--slider-nav .slider-dot.second').removeClass('active');
        } else {
          jQuery('.related--slider-nav button.next').removeClass('active');
          jQuery('.related--slider-nav button.prev').addClass('active');
          jQuery('.related--slider-nav .slider-dot.first').removeClass('active');
          jQuery('.related--slider-nav .slider-dot.second').addClass('active');
        }
      });
    </script>
<?php
    wp_reset_postdata();
  } else {
    echo 'No related articles found.';
  }
}
?>
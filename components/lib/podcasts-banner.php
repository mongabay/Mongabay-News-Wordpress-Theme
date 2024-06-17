<?php
function podcasts_banner()
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
      <h1>Uncover a world of thought provoking <span class="icon icon-podcast">podcasts</span></h1>
      <div class="in-column gap--20">

        <div class="full-width sound-wave">
          <div class="article--container narrow">
            <?php if (has_post_thumbnail()) { ?>
              <div class="featured-image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
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
              <span class="date"><?php the_time('j M Y'); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php }
}
?>
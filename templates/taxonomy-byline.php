<?php

/**
 * The template for displaying taxonomy byline
 * as an archive
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header();
$title = get_query_var('term');
$term = get_query_var('list');
$first = get_query_var('nc1');

$byline = get_term_by('slug', $title, 'byline');
$byline_name = $byline->name;
$byline_description = $byline->description;

?>

<div class="container in-column in-row ph--40 pv--40 gap--80">
  <div class="col--20">
    <div class="byline--overview byline-avatar">
      <?php echo get_avatar($byline->term_id); ?>
    </div>
  </div>
  <div class="byline--info col--80">
    <h1 class=""><?php echo $byline_name; ?></h1>
    <?php if ($byline_description) { ?>
      <p><?php echo $byline_description; ?></p>
    <?php } ?>
  </div>
</div>
<div class="container in-column ph--40 pv--40">
  <?php if (have_posts()) { ?>
    <?php
    //get query object
    global $wp_query;
    $total = $wp_query->found_posts;
    ?>

    <div id="results">
      <div id="results-header">
        <div id="results-header-left">
          <a href="<?php get_home_url() ?>/feed/?post_type=post&byline=<?php echo $title; ?>" target="_blank" id="results-rss" class="theme--button primary simple">RSS</a>
          <div id="results-total"><?php echo $total; ?> <?php _e($total > 1 ? 'stories' : 'story', 'mongabay'); ?></div>
        </div>
        <div id="results-view-toggles">
          <button id="list-view">L</button>
          <button id="grid-view" class="active">G</button>
        </div>
      </div>
      <div id="post-results" class="grid-view">
        <?php
        // Start the Loop
        while (have_posts()) :
          the_post();
        ?>
          <div class="article--container pv--8">
            <a href="<?php the_permalink(); ?>">
              <?php if (has_post_thumbnail()) { ?>
                <div class="featured-image">
                  <?php echo get_icon(get_the_ID()); ?>
                  <?php the_post_thumbnail('medium') ?>
                </div>
              <?php }; ?>
              <div class="title headline">
                <h3><?php the_title(); ?></h3>
              </div>
              <div class="meta pv--8">
                <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                <span class="date"><?php the_time('j F Y'); ?></span>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      </div>
      <script>
        const gridViewButton = document.getElementById("grid-view");
        const listViewButton = document.getElementById("list-view");
        const resultsList = document.getElementById("post-results");

        gridViewButton.addEventListener("click", () => {
          resultsList.classList.remove("list-view");
          resultsList.classList.add("grid-view");
          gridViewButton.classList.add("active");
          listViewButton.classList.remove("active");
        });

        listViewButton.addEventListener("click", () => {
          resultsList.classList.remove("grid-view");
          resultsList.classList.add("list-view");
          listViewButton.classList.add("active");
          gridViewButton.classList.remove("active");
        });
      </script>
    </div>
    <div class="pagination container pv--40 centered gap--20">
      <?php mongabay_pagination(); ?>
    </div>
  <?php } else {
    _e('No results found', 'mongabay');
  }
  ?>
</div>
<?php inspiration_banner(); ?>
<?php get_footer(); ?>
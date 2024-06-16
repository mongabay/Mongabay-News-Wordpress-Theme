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
$byline_id = $byline->term_id;
$byline_email = get_term_meta($byline_id, 'email', true);
$byline_web = get_term_meta($byline_id, 'web', true);
$byline_x = get_term_meta($byline_id, 'authors_twitter_account', true);
$byline_fb = get_term_meta($byline_id, 'authors_facebook_account', true);
$byline_type = get_term_meta($byline_id, 'author_type', true);
$byline_avatar = get_term_meta($byline_id, 'cover_image_url', true);
?>

<div class="container in-column in-row ph--40 pv--40 gap--80">
  <div class="col--20">
    <div class="byline--overview byline-avatar">
      <?php if ($byline_avatar) { ?>
        <img src="<?php echo $byline_avatar; ?>" alt="<?php echo $byline_name; ?>">
      <?php } else {
        echo get_avatar($byline->term_id);
      }; ?>
    </div>
  </div>
  <div class="container in-column byline--info col--80 gap--20">
    <h1 class=""><?php echo $byline_name; ?></h1>
    <?php if ($byline_description) { ?>
      <div class="section-title gap--16">
        <h4><?php _e('About', 'mongabay'); ?></h4>
        <div class="divider"></div>
      </div>
      <p><?php echo $byline_description; ?></p>
    <?php } ?>
    <div class="container in-row gap--20">
      <?php if ($byline_email) { ?>
        <a href="mailto:<?php echo $byline_email; ?>">Email address</a>
      <?php } ?>
      <?php if ($byline_web) { ?>
        <div class="col--50">
          <a href="<?php echo $byline_web; ?>" target="_blank">Website</a>
        </div>
      <?php } ?>
    </div>
    <div class="container in-row gap--20">
      <?php if ($byline_x) { ?>
        <div class="col--50">
          <a href="https://x.com/<?php echo $byline_x; ?>" target="_blank">X</a>
        </div>
      <?php } ?>
      <?php if ($byline_fb) { ?>
        <div class="col--50">
          <a href="https://facebook.com/<?php echo $byline_fb; ?>" target="_blank">Facebook</a>
        </div>
      <?php } ?>
    </div>
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
              <div class="post-meta pv--8">
                <span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
                <span class="date"><?php the_time('j M Y'); ?></span>
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
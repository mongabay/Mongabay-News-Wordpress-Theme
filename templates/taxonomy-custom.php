<?php

/**
 * The template for displaying topic and location listings.
 */

defined('ABSPATH') || exit;

global $wp_query, $post;

$selected_terms = array();
if (get_query_var('section') === 'list') {
  foreach (array('nc1', 'nc2', 'nc3') as $query_var) {
    $term = mongabay_get_list_term(get_query_var($query_var));
    if ($term) {
      $selected_terms[] = $term;
    }
  }
} else {
  $queried_term = get_queried_object();
  if ($queried_term instanceof WP_Term && in_array($queried_term->taxonomy, array('topic', 'location'), true)) {
    $selected_terms[] = $queried_term;
  }
}

$term_names = array();
$topic_terms = array();
$location_terms = array();
foreach ($selected_terms as $selected_term) {
  $term_names[] = $selected_term->name;
  if ($selected_term->taxonomy === 'topic') {
    $topic_terms[] = $selected_term->slug;
  } elseif ($selected_term->taxonomy === 'location') {
    $location_terms[] = $selected_term->slug;
  }
}

$rss_params = array('s' => '', 'type' => 'post');
if (!empty($topic_terms)) {
  $rss_params['topic'] = implode(',', $topic_terms);
}
if (!empty($location_terms)) {
  $rss_params['location'] = implode(',', $location_terms);
}
$rss_url = add_query_arg($rss_params, home_url('/feed/'));
$listing_title = implode(' and ', $term_names);
$posts = $wp_query->posts;
$total = (int) $wp_query->found_posts;

$render_card = static function ($listing_post, $thumbnail_size = 'thumbnail-medium', $heading = 'h3') use (&$post) {
  $post = $listing_post;
  setup_postdata($post);
?>
  <div class="article--container">
    <a href="<?php the_permalink(); ?>">
      <?php if (has_post_thumbnail()) : ?>
        <div class="featured-image">
          <?php echo get_icon(get_the_ID()); ?>
          <?php the_post_thumbnail($thumbnail_size); ?>
        </div>
      <?php endif; ?>
      <div class="title headline pv--8">
        <<?php echo esc_attr($heading); ?>><?php the_title(); ?></<?php echo esc_attr($heading); ?>>
      </div>
      <div class="post-meta pv--8">
        <span class="byline"><?php echo esc_html(getPostBylines(get_the_ID())); ?></span>
        <span class="date"><?php the_time('j M Y'); ?></span>
      </div>
    </a>
  </div>
<?php
};
?>

<div class="container content in-column gap--20">
  <div class="search" data-listing-filters data-list-url="<?php echo esc_url(home_url('/list/')); ?>" data-home-url="<?php echo esc_url(home_url('/')); ?>">
    <h1 class="screen-reader-text"><?php echo esc_html($listing_title ?: __('Latest news', 'mongabay')); ?></h1>
    <div class="tax-wrapper listing-filter-row" data-filter-row>
      <?php foreach ($selected_terms as $index => $selected_term) : ?>
        <div class="tax-item-wrapper listing-filter-field" data-filter-field data-taxonomy="<?php echo esc_attr($selected_term->taxonomy); ?>" data-slug="<?php echo esc_attr($selected_term->slug); ?>">
          <div class="listing-filter-selection">
            <button type="button" class="listing-filter-pill" data-edit-filter data-selected-pill aria-label="<?php echo esc_attr(sprintf(__('Change %s filter', 'mongabay'), $selected_term->name)); ?>" aria-expanded="false">
              <span><?php echo esc_html($selected_term->name); ?></span>
              <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                <path d="m5 9 7 7 7-7" />
              </svg>
            </button>
            <input type="text" data-filter-input placeholder="<?php echo esc_attr($selected_term->name); ?>" aria-label="<?php echo esc_attr(sprintf(__('Search to replace %s', 'mongabay'), $selected_term->name)); ?>" aria-autocomplete="list" aria-expanded="false" autocomplete="off" hidden>
            <button type="button" class="listing-filter-remove" aria-label="<?php echo esc_attr(sprintf(__('Remove %s filter', 'mongabay'), $selected_term->name)); ?>" data-remove-filter>
              <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                <path d="m4 4 16 16M20 4 4 20" />
              </svg>
            </button>
          </div>
          <div class="listing-filter-dropdown dropdown hide" data-filter-suggestions role="listbox"></div>
          <div class="listing-filter-dropdown dropdown hide" data-filter-results role="listbox"></div>
        </div>
      <?php endforeach; ?>
      <?php if (count($selected_terms) < 3) : ?>
        <button type="button" class="listing-filter-add" data-add-filter aria-label="<?php esc_attr_e('Add a topic or location filter', 'mongabay'); ?>">
          <svg aria-hidden="true" viewBox="0 0 24 24" focusable="false">
            <path d="M12 5v14M5 12h14" />
          </svg>
        </button>
      <?php endif; ?>
    </div>
  </div>
  <div class="container in-row space-between align-center gap--16">
    <a href="<?php echo esc_url($rss_url); ?>" target="_blank" rel="noopener noreferrer" class="theme--button simple"><?php esc_html_e('RSS', 'mongabay'); ?></a>
    <span><?php echo esc_html(number_format_i18n($total)); ?> <?php echo esc_html(_n('story', 'stories', $total, 'mongabay')); ?></span>
  </div>

  <?php if (!empty($posts)) : ?>
    <div class="container in-row gap--20">
      <div class="column--70 latest-featured">
        <?php $render_card($posts[0], 'large', 'h1'); ?>
      </div>
      <?php if (isset($posts[1])) : ?>
        <div class="column--30 latest-news gap--16">
          <?php
          $render_card($posts[1]);
          if (isset($posts[2])) {
            $render_card($posts[2]);
          }
          ?>
        </div>
      <?php endif; ?>
    </div>

    <?php $first_grid = array_slice($posts, 3, 8); ?>
    <?php if (!empty($first_grid)) : ?>
      <div class="container grid--4 repeat gap--16 pv--16">
        <?php foreach ($first_grid as $listing_post) : ?>
          <?php $render_card($listing_post); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="container full-width pv--40">
      <?php banner(
        get_subscribe_link_local(get_current_blog_id()),
        'Stay updated',
        'Stay informed with news and inspiration from nature’s frontline.',
        'Newsletter',
        'bg-theme-accent full-width ph--40 pv--40',
        'extra-large',
        ''
      ); ?>
    </div>

    <?php $second_grid = array_slice($posts, 11); ?>
    <?php if (!empty($second_grid)) : ?>
      <div class="container grid--4 repeat gap--16 pv--16">
        <?php foreach ($second_grid as $listing_post) : ?>
          <?php $render_card($listing_post); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
    <div class="pagination container pv--40 centered gap--20">
      <?php if (get_previous_posts_page_link()) : ?>
        <a class="theme--button outlined" href="<?php echo esc_url(get_previous_posts_page_link()); ?>"><?php esc_html_e('Previous stories', 'mongabay'); ?></a>
      <?php endif; ?>
      <?php if (get_next_posts_page_link($wp_query->max_num_pages)) : ?>
        <a class="theme--button primary" href="<?php echo esc_url(get_next_posts_page_link($wp_query->max_num_pages)); ?>"><?php esc_html_e('Explore more', 'mongabay'); ?><span class="icon icon-right"></span></a>
      <?php endif; ?>
    </div>
  <?php else : ?>
    <p><?php esc_html_e('No results found', 'mongabay'); ?></p>
  <?php endif; ?>
</div>
<?php inspiration_banner(); ?>
</main>
<?php get_footer(); ?>
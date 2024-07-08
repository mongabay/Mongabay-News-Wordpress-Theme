<?php

function getPostBylines($post_id)
{
  return strip_tags(get_the_term_list($post_id, 'byline', '', ', ', ''));
}

// article
include(get_template_directory() . '/components/lib/article/article-headline.php');
include(get_template_directory() . '/components/lib/article/article-credits.php');
include(get_template_directory() . '/components/lib/article/article-bulletpoints.php');
include(get_template_directory() . '/components/lib/article/article-terms.php');
include(get_template_directory() . '/components/lib/article/social-share.php');
include(get_template_directory() . '/components/lib/article/shorts-article-card.php');

// banners
include(get_template_directory() . '/components/lib/banners/banner.php');
include(get_template_directory() . '/components/lib/banners/inspiration-banner.php');
include(get_template_directory() . '/components/lib/banners/podcasts-banner.php');

// lists
include(get_template_directory() . '/components/lib/lists/articles-listing.php');
include(get_template_directory() . '/components/lib/lists/featured-articles-listing.php');
include(get_template_directory() . '/components/lib/lists/series-articles-listing.php');
include(get_template_directory() . '/components/lib/lists/articles-listing-condensed.php');
include(get_template_directory() . '/components/lib/lists/articles-listing-in-columns.php');
include(get_template_directory() . '/components/lib/lists/shorts-listing.php');

// sliders
include(get_template_directory() . '/components/lib/sliders/featured-articles-slider.php');
include(get_template_directory() . '/components/lib/sliders/tools-slider.php');
include(get_template_directory() . '/components/lib/sliders/formats-slider.php');

// misc
include(get_template_directory() . '/components/lib/topics-section.php');
include(get_template_directory() . '/components/lib/videos-latest.php');
include(get_template_directory() . '/components/lib/podcasts-topics-section.php');
include(get_template_directory() . '/components/lib/specials-latest.php');
include(get_template_directory() . '/components/lib/post-icon.php');
include(get_template_directory() . '/components/lib/share-icons-grid.php');

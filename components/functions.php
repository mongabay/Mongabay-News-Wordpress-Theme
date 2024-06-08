<?php

function getPostBylines($post_id)
{
  return strip_tags(get_the_term_list($post_id, 'byline', '', ', ', ''));
}

include(get_template_directory() . '/components/lib/banner.php');
include(get_template_directory() . '/components/lib/articles-listing.php');
include(get_template_directory() . '/components/lib/featured-articles-listing.php');
include(get_template_directory() . '/components/lib/inspiration-banner.php');
include(get_template_directory() . '/components/lib/series-articles-listing.php');
include(get_template_directory() . '/components/lib/featured-articles-slider.php');
include(get_template_directory() . '/components/lib/articles-listing-condensed.php');
include(get_template_directory() . '/components/lib/topics-section.php');
include(get_template_directory() . '/components/lib/formats-slider.php');
include(get_template_directory() . '/components/lib/videos-latest.php');
include(get_template_directory() . '/components/lib/podcasts-banner.php');
include(get_template_directory() . '/components/lib/articles-listing-in-columns.php');
include(get_template_directory() . '/components/lib/podcasts-topics-section.php');
include(get_template_directory() . '/components/lib/tools-slider.php');
include(get_template_directory() . '/components/lib/social-share.php');

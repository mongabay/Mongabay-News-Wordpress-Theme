<?php
function get_default_formats()
{
  return array('post', 'custom-story', 'videos', 'podcasts', 'specials', 'short-article');
}

function get_default_search_formats($formats)
{
  return str_replace('-', '_', join('+', $formats));
}

function get_subscribe_link()
{
  return 'https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4';
}

function get_donate_link()
{
  return home_url() . '/donate';
}

function get_menu_item($key, $option)
{
  $menu_items = array(
    'features' => array(
      'title' => __('Features', 'mongabay'),
      'slug' => __('features', 'mongabay')
    ),
    'videos' => array(
      'title' => __('Videos', 'mongabay'),
      'slug' => __('videos', 'mongabay')
    ),
    'podcasts' => array(
      'title' => __('Podcasts', 'mongabay'),
      'slug' => __('podcasts', 'mongabay')
    ),
    'specials' => array(
      'title' => __('Specials', 'mongabay'),
      'slug' => __('specials', 'mongabay')
    ),
    'articles' => array(
      'title' => __('Articles', 'mongabay'),
      'slug' => __('articles', 'mongabay')
    ),
    'shorts' => array(
      'title' => __('Shorts', 'mongabay'),
      'slug' => __('shorts', 'mongabay')
    ),
    'about' => array(
      'title' => __('About', 'mongabay'),
      'slug' => __('about', 'mongabay')
    ),
    'team' => array(
      'title' => __('Team', 'mongabay'),
      'slug' => __('team', 'mongabay')
    ),
    'contact' => array(
      'title' => __('Contact', 'mongabay'),
      'slug' => __('contact', 'mongabay')
    ),
    'shortnews' => array(
      'title' => __('Short News', 'mongabay'),
      'slug' => __('shorts', 'mongabay')
    ),
    'featurestories' => array(
      'title' => __('Feature Stories', 'mongabay'),
      'slug' => __('features', 'mongabay')
    ),
    'thelatest' => array(
      'title' => __('The Latest', 'mongabay'),
      'slug' => __('list/2024', 'mongabay')
    ),
    'exploreall' => array(
      'title' => __('Explore All', 'mongabay'),
      'slug' => __('?s=&formats=post+custom_story+videos+podcasts+specials+short_article', 'mongabay')
    ),
    'footerabout' => array(
      'title' => __('About ', 'mongabay'),
      'slug' => __('https://www.mongabay.com/about/', 'mongabay')
    ),
    'footerteam' => array(
      'title' => __('Team', 'mongabay'),
      'slug' => __('team', 'mongabay')
    ),
    'footercontact' => array(
      'title' => __('Contact', 'mongabay'),
      'slug' => __('contact', 'mongabay')
    ),
  );

  return $menu_items[$key][$option];
}

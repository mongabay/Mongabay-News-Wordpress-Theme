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
  return 'https://mongabay.org/donate';
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

function get_enabled_features($feature)
{
  $site_id = get_current_blog_id();

  $features = array(
    20 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => true,
      'donate' => true,
    ),
    25 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => true,
      'donate' => true,
    ),
    26 => array(
      'podcasts' => false,
      'videos' => true,
      'specials' => true,
      'shorts' => false,
      'donate' => true,
    ),
    29 => array(
      'podcasts' => false,
      'videos' => true,
      'specials' => true,
      'shorts' => false,
      'donate' => true,
    ),
    30 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => false,
      'donate' => false,
    ),
  );

  return $features[$site_id][$feature];
}

function get_social_links($site_id)
{
  switch ($site_id) {
    case 20:
      // News
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay.com/',
        'instagram' => 'https://www.instagram.com/mongabay/',
        'youtube' => 'https://www.youtube.com/channel/UCnrubbmyCz8krGnpsbhJRYg/videos',
        'x' => 'https://www.x.com/mongabay',
        'facebook' => 'https://www.facebook.com/mongabay',
        'mastodon' => 'https://mastodon.green/@mongabay',
        'android' => 'https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en',
        'apple' => 'https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/',
      );
    case 30:
      // India
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay-india/ ',
        'instagram' => 'https://www.instagram.com/mongabayindia/',
        'youtube' => 'https://www.youtube.com/@MongabayIndia',
        'x' => 'https://x.com/MongabayIndia',
        'facebook' => 'https://www.facebook.com/mongabayindia',
        'android' => 'https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en',
        'apple' => 'https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/',
      );
    default:
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay.com/',
        'instagram' => 'https://www.instagram.com/mongabay/',
        'youtube' => 'https://www.youtube.com/channel/UCnrubbmyCz8krGnpsbhJRYg/videos',
        'x' => 'https://www.x.com/mongabay',
        'facebook' => 'https://www.facebook.com/mongabay',
        'mastodon' => 'https://mastodon.green/@mongabay',
        'android' => 'https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en',
        'apple' => 'https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/',
      );
  }
}

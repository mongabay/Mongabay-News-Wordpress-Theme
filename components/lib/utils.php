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
      'slug' => __('list/', 'mongabay')
    ),
    'exploreall' => array(
      'title' => __('Explore All', 'mongabay'),
      'slug' => __('?s=&formats=post+custom_story+videos+podcasts+specials+short_article', 'mongabay')
    ),
    'footerabout' => array(
      'title' => __('About', 'mongabay'),
      'slug' => __('about', 'mongabay')
    ),
    'footerteam' => array(
      'title' => __('Team', 'mongabay'),
      'slug' => __('team', 'mongabay')
    ),
    'footercontact' => array(
      'title' => __('Contact', 'mongabay'),
      'slug' => __('contact', 'mongabay')
    ),
    'impacts' => array(
      'title' => __('Impacts', 'mongabay'),
      'slug' => __('impacts', 'mongabay')
    ),
    'submissions' => array(
      'title' => __('Submissions', 'mongabay'),
      'slug' => __('submissions', 'mongabay')
    ),
    'terms' => array(
      'title' => __('Terms of Use', 'mongabay'),
      'slug' => __('terms', 'mongabay')
    ),
  );

  return $menu_items[$key][$option];
}

function get_enabled_features($feature)
{
  $site_id = get_current_blog_id();

  $features = array(
    // News
    20 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => true,
      'donate' => true,
    ),
    // Latam
    25 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => true,
      'donate' => true,
    ),
    // French
    26 => array(
      'podcasts' => false,
      'videos' => true,
      'specials' => true,
      'shorts' => false,
      'donate' => true,
    ),
    // Brazil
    29 => array(
      'podcasts' => false,
      'videos' => true,
      'specials' => true,
      'shorts' => false,
      'donate' => true,
    ),
    // India
    30 => array(
      'podcasts' => true,
      'videos' => true,
      'specials' => true,
      'shorts' => true,
      'donate' => false,
    ),
    // Hindi
    35 => array(
      'podcasts' => false,
      'videos' => true,
      'specials' => false,
      'shorts' => false,
      'donate' => true,
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
        'tiktok' => 'https://www.tiktok.com/@mongabay',
        'bluesky' => 'https://bsky.app/profile/mongabay.com',
        'facebook' => 'https://www.facebook.com/mongabay',
        'mastodon' => 'https://mastodon.green/@mongabay',
        'android' => 'https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en',
        'apple' => 'https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/',
        'reddit' => 'https://www.reddit.com/r/naturesfrontline/',
      );
    case 25:
      // Latam
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay-latam/',
        'instagram' => 'https://www.instagram.com/mongabaylatam/',
        'youtube' => 'https://www.youtube.com/@MongabayLatam',
        'x' => 'https://x.com/mongabaylatam',
        'tiktok' => 'https://www.tiktok.com/@mongabaylatam',
        'facebook' => 'https://www.facebook.com/MongabayLatam/',
      );
    case 26:
      // French
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay-africa/',
        'instagram' => 'https://www.instagram.com/MongabayAfrique/',
        'youtube' => 'https://www.youtube.com/@MongabayAfrique',
        'facebook' => 'https://www.facebook.com/MongabayAfrique/',
      );
    case 29:
      // Brazil
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay-brasil/',
        'instagram' => 'https://www.instagram.com/mongabaybrasil/',
        'youtube' => 'https://www.youtube.com/@MongabayBrasil',
        'x' => 'https://x.com/mongabay_brasil/',
        'tiktok' => 'https://www.tiktok.com/@mongabaylatam',
        'facebook' => 'https://www.facebook.com/MongabayBrasil/',
        'whatsapp' => 'https://www.whatsapp.com/channel/0029VbAjoLrIiRp1IU8S7n2s?fbclid=PAZXh0bgNhZW0CMTEAAacJhiTjSG8StSAK0xc-1j1-32FqXVJF741T9UkT2SgMoyAz08iw3335Vct0xg_aem__r5vbyrteHWzZyklcnRKeQ',
      );
    case 30:
      // India
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabay-india/',
        'instagram' => 'https://www.instagram.com/mongabayindia/',
        'youtube' => 'https://www.youtube.com/@MongabayIndia',
        'x' => 'https://x.com/mongabayindia',
        'facebook' => 'https://www.facebook.com/mongabayindia/',
        'android' => 'https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en',
        'apple' => 'https://apple.news/T7BxfNfp4QAiuOPNsUcn04Q/',
        'telegram' => 'https://t.me/s/mongabayindiaofficial/',
      );
    case 35:
      // Hindi
      return array(
        'linkedin' => 'https://www.linkedin.com/company/mongabayhindi/',
        'instagram' => 'https://www.instagram.com/mongabayhindi/',
        'youtube' => 'https://www.youtube.com/@mongabayhindi',
        'facebook' => 'https://www.facebook.com/mongabayhindi/',
        'telegram' => 'https://t.me/s/mongabayindiaofficial/',
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

function get_subscribe_link_local($site_id)
{
  switch ($site_id) {
    case 20:
      // News
      return 'https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4';
    case 25:
      // Latam
      return 'https://es.mongabay.com/boletin-de-noticias/';
    case 30:
      // India
      return 'https://mongabay.us17.list-manage.com/subscribe?u=2dd04db80c7aee01e77033c9c&id=ac74ea7c07&mc_cid=4c25010d1b&mc_eid=561303a745';
    default:
      return 'https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4';
  }
}

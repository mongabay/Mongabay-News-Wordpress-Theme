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

<?php
function getDefaultFormats()
{
  return array('post', 'custom-story', 'videos', 'podcasts', 'specials', 'short-article');
}

function getDefaultSearchFormats()
{
  return str_replace('-', '_', join('+', getDefaultFormats()));
}

function getSubscribeLink()
{
  return 'https://mongabay.us14.list-manage.com/subscribe?u=80161fe385606408293ae0e51&id=940652e1f4';
}

<?php
function get_icon($post_id = null)
{
  $type = get_post_type($post_id);

  $icons = array(
    'videos' => 'icon-play',
    'podcasts' => 'icon-podcast',
    'specials' => 'icon-specials',
    'short-article' => 'icon-shorts',
    'custom-story' => 'icon-custom-story',
  );

  if (!isset($icons[$type])) {
    return;
  }

  if ($type === 'short-article') {
    $terms = get_the_terms($post_id, 'shorts_format');
    $slug = !empty($terms) && !is_wp_error($terms) ? strtolower($terms[0]->slug) : '';
    $format_icons = array('video' => 'icon-play', 'audio' => 'icon-podcast');
    $icon_class = $format_icons[$slug] ?? $icons['short-article'];
  } else {
    $icon_class = $icons[$type];
  }

  return '<div class="post-icon"><span class="icon ' . $icon_class . '"></span></div>';
}

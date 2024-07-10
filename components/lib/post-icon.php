<?php
function get_icon($post_id = null)
{
  $type = get_post_type($post_id);

  if ($type !== 'post' && ($type === 'videos' || $type === 'podcasts' || $type === 'specials')) {
    $icons = array(
      'videos' => 'icon-play',
      'podcasts' => 'icon-podcast',
      'specials' => 'icon-specials',
    );

    return '<div class="post-icon"><span class="icon ' . $icons[$type] . '"></span></div>';
  }
}

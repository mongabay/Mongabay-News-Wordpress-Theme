<?php
function get_icon($post_id = null)
{
  $type = get_post_type($post_id);

  if ($type !== 'post' && $type === 'videos' || $type === 'podcasts') {
    return '<div class="post-icon"><span class="icon ' . ($type === 'videos' ? 'icon-play' : 'icon-podcast') . '"></span></div>';
  }
}

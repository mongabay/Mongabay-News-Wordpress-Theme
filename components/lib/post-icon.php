<?php
function get_icon($post_id = null)
{
  $type = get_post_type($post_id);

  $icon_video = '<svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M0 11.3775V1.32078C0 1.04548 0.0987654 0.814708 0.296296 0.628473C0.493827 0.442239 0.72428 0.349121 0.987654 0.349121C1.06996 0.349121 1.15638 0.361267 1.24691 0.385558C1.33745 0.40985 1.42387 0.446287 1.50617 0.49487L9.55556 5.52321C9.7037 5.62038 9.81481 5.74183 9.88889 5.88758C9.96296 6.03333 10 6.18718 10 6.34912C10 6.51106 9.96296 6.66491 9.88889 6.81066C9.81481 6.95641 9.7037 7.07787 9.55556 7.17503L1.50617 12.2034C1.42387 12.252 1.33745 12.2884 1.24691 12.3127C1.15638 12.337 1.06996 12.3491 0.987654 12.3491C0.72428 12.3491 0.493827 12.256 0.296296 12.0698C0.0987654 11.8835 0 11.6528 0 11.3775Z" fill="inherit"/>
  </svg>';
  $icon_podcast = '<svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
  <path d="M8 4.34912L8 12.3491" stroke="inherit" stroke-width="2" stroke-linecap="round"/>
  <path d="M11 3.01587L11 13.6825" stroke="inherit" stroke-width="2" stroke-linecap="round"/>
  <path d="M5 1.68237L5 15.0157" stroke="inherit" stroke-width="2" stroke-linecap="round"/>
  <path d="M14 7.68237L14 9.01571" stroke="inherit" stroke-width="2" stroke-linecap="round"/>
  <path d="M2 5.01587L2 11.6825" stroke="inherit" stroke-width="2" stroke-linecap="round"/>
  </svg>
  ';

  if ($type !== 'post') {
    return '<div class="post-icon">' . ($type === 'videos' ? $icon_video : $icon_podcast) . '</div>';
  }
}

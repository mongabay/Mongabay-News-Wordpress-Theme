<?php
function article_terms(int $post_id)
{
  echo '<div class="section-title gap--16"><h4>';
  _e('Topics', 'mongabay');
  echo '</h4><div class="divider"></div></div>';
  echo '<div class="container wrapped">';
  echo get_the_term_list($post_id, 'topic', '', '', '');
  echo get_the_term_list($post_id, 'location', '', '', '');
  echo get_the_term_list($post_id, 'entity', '', '', '');
  echo '</div>';
}

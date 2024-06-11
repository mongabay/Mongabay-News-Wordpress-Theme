<?php
function article_terms(int $post_id)
{
  echo get_the_term_list($post_id, 'topic', '', '', '');
  echo get_the_term_list($post_id, 'location', '', '', '');
  echo get_the_term_list($post_id, 'entity', '', '', '');
}

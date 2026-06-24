<?php
function shorts_grid($post_counter, $banner)
{
  // Get the post format for the current post
  $id = get_the_ID();
  $post_format = get_the_terms($id, 'shorts_format')[0]->slug ?? '';

  if ($post_counter === 1) {
    echo '<div class="container in-row gap--40">'; //first grid start
    echo '<div class="column--60">'; // first grid first column
    shorts_article_card($id, true, '', $post_counter, $post_format);
    echo '</div>'; // close first grid first column
  }
  if ($post_counter === 2) {
    echo '<div class="column--40 in-column gap--40">';
    shorts_article_card($id, false, '', $post_counter, $post_format);
  }

  if ($post_counter === 3) {
    shorts_article_card($id, false, '', $post_counter, $post_format);
    echo '</div>'; // close first grid second column
    echo '</div>'; // close first grid
  }

  if ($post_counter === 4) {
    echo '<div class="container in-row gap--40 pv--40">'; //second grid start
    echo '<div class="column--40 in-column gap--40">'; //second grid first column
    shorts_article_card($id, false, '', $post_counter, $post_format);
  }

  if ($post_counter === 5) {
    shorts_article_card($id, false, '', $post_counter, $post_format);
    echo '</div>'; // close second grid first column
  }

  if ($post_counter === 6) {
    echo '<div class="column--60">'; //second grid second column
    shorts_article_card($id, true,  '', $post_counter, $post_format);
    echo '</div>'; // close second grid second column
    echo '</div>'; // close second grid
  }

  if ($post_counter === 7) {
    echo '<div class="container in-row gap--40">'; //third grid start
    echo '<div class="column--40 in-column gap--40">'; //third grid first column
    shorts_article_card($id, false, '', $post_counter, $post_format);

    if ($banner) {
      echo $banner;
      echo '</div>'; // close third grid first column
    };
  }

  if ($post_counter === 8) {
    if (!$banner) {
      shorts_article_card($id, false, '', $post_counter, $post_format);
      echo '</div>'; // close third grid first column
    } else {
      echo '<div class="column--60">'; //third grid second column
      shorts_article_card($id, true, '', $post_counter, $post_format);
      echo '</div>'; // close third grid second column
      echo '</div>'; // close third grid
    }
  }

  if ($post_counter === 9) {
    echo '<div class="column--60">'; //third grid second column
    shorts_article_card($id, true, '', $post_counter, $post_format);
    echo '</div>'; // close third grid second column
    echo '</div>'; // close third grid
  }
}

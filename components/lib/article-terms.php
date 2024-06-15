<?php
function article_terms(int $post_id)
{
  echo '<div class="section-title gap--16"><h4>';
  _e('Topics', 'mongabay');
  echo '</h4><div class="divider"></div></div>';
  echo '<div id="article-taxonomies" class="container wrapped" style="position: relative">';
  echo get_the_term_list($post_id, 'topic', '', '', '');
  echo get_the_term_list($post_id, 'location', '', '', '');
  echo get_the_term_list($post_id, 'entity', '', '', '');
  echo '</div>';
  echo '<div id="expander-container" class="tags container full-width"><button class="content-expander"><span>';
  _e('See Topics', 'mongabay');
  echo '</span></button></div>';
?>
  <script>
    jQuery(document).ready(function() {
      const wrapperHeight = document.getElementById('article-taxonomies').scrollHeight;

      if (wrapperHeight <= 130) {
        jQuery('#expander-container').remove();

        return;
      }

      jQuery('#expander-container.tags .content-expander').click(function() {
        jQuery('#article-taxonomies').toggleClass('visible');
        jQuery(this).toggleClass('visible');
      });
    });
  </script>
<?php }

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
  echo '<div id="expander-container" class="container full-width"><button class="content-expander"><span>';
  _e('See Topics', 'mongabay');
  echo '</span></button></div>';
?>
  <script>
    (function($) {
      $(document).ready(function() {
        const wrapperHeight = $('#article-taxonomies').height();

        if (wrapperHeight < 80) {
          $('.content-expander').remove();

          return;
        }

        $('#expander-container .content-expander').click(function() {
          $('#article-taxonomies').toggleClass('visible');
          $(this).toggleClass('visible');
        });
      });
    })(jQuery);
  </script>
<?php }

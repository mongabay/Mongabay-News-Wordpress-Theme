<?php
function article_bulletpoints(int $post_id)
{ ?>
  <?php
  $mog_count = 0;
  for ($n = 0; $n < 4; $n++) {
    $single_bullet = get_post_meta($post_id, "mog_bullet_" . $n . "_mog_bulletpoint", true);
    if (!empty($single_bullet)) {
      $mog_count = $mog_count + 1;
    }
  }
  if ((int)$mog_count > 0 && get_post_meta($post_id, "mog_bullet_0_mog_bulletpoint", true)) {
    echo '<div class="bulletpoints-wrapper"><div class="bulletpoints"><ul>';

    for ($i = 0; $i < $mog_count; $i++) {
      echo "<li><em>" . get_post_meta($post_id, "mog_bullet_" . $i . "_mog_bulletpoint", true) . "</em></li>";
    }

    echo '</ul></div><div id="expander-container" class="bullets container full-width"><button class="content-expander"><span>' . __('See All Key Ideas', 'mongabay') . '</span></button></div></div>';
  }

  ?>
  <script>
    (function($) {
      $(document).ready(function() {
        const bulletPoints = $('.bulletpoints');
        const toggle = $('.bulletpoints-wrapper .content-expander');

        if (bulletPoints.length > 0) {
          const bulletPointsHeight = bulletPoints[0].scrollHeight;

          if (bulletPointsHeight && bulletPointsHeight <= 170) {
            toggle.remove();
          }

          toggle.click(function() {
            bulletPoints.toggleClass('visible');
            $('#expander-container.bullets').toggleClass('visible');
            $(this).toggleClass('visible');
          });
        }
      });
    })(jQuery);
  </script>
<?php } ?>
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

    echo '</ul></div><button class="content-expander"><span>' . __('See All Key Ideas', 'mongabay') . '</span></button></div>';
  }
  ?>
  <script>
    (function($) {
      $(document).ready(function() {
        const bulletPoints = $('.bulletpoints');

        if (bulletPoints.length === 0) {
          return;
        }

        const bulletPointsHeight = document.querySelector('.bulletpoints').scrollHeight;
        const toggle = $('.bulletpoints-wrapper .content-expander');

        if (bulletPointsHeight < 120) {
          toggle.remove();
        }

        toggle.click(function() {
          bulletPoints.toggleClass('visible');
          $(this).toggleClass('visible');
        });
      });
    })(jQuery);
  </script>
<? }
?>
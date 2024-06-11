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
    echo '<div class="bulletpoints" data-tie-toggle=".bulletpoints ul"><ul>';
    for ($i = 0; $i < $mog_count; $i++) {
      if ($i >= 2) {
        echo "<li style='display:none;'><em>" . get_post_meta($post_id, "mog_bullet_" . $i . "_mog_bulletpoint", true) . "</em></li>";
      } else {
        echo "<li><em>" . get_post_meta($post_id, "mog_bullet_" . $i . "_mog_bulletpoint", true) . "</em></li>";
      }
    }
    echo '</ul>';
    if ($mog_count > 2) {
      echo '<button class="content-expander"><span>' . __('See All Key Ideas', 'mongabay') . '</span></button>';
    }
    echo '</div>';
  }
  ?>
  <script>
    (function($) {
      $(document).ready(function() {
        $('.bulletpoints .content-expander').click(function() {
          $(this).prev('ul').find('li:nth-child(n+3)').toggle();
          if ($(this).prev('ul').find('li:nth-child(n+3)').is(':visible')) {
            $(this).addClass('visible');
          } else {
            $(this).removeClass('visible');
          }
        });
      });
    })(jQuery);
  </script>
<? }
?>
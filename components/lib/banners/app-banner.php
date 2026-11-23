<?php
function app_banner($number_of_phones = 1)
{ ?>
  <div id="app-banner" class="full-width <?php echo $number_of_phones === 1 ? 'bg-theme-accent' : 'bg-theme-secondary'; ?>">
    <div class="container">
      <div class="title column--40 in-column pv--80 ph--40 gap--16 justify-center">
        <h1><? echo __('Get the Mongabay App', 'mongabay'); ?></h1>
        <p><? echo __('Personalise your news feed and follow the environmental topics and regions that matter most to you.', 'mongabay'); ?></p>
        <div class="container gap--20 in-row">
          <a href="https://play.google.com/store/apps/details?id=com.newsmongabay&hl=en" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/google_play.svg" alt="Download on Google Play"></a>
          <a href="https://apps.apple.com/us/app/mongabay-environmental-news/id6802597016" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/app_store.svg" alt="Download on the App Store"></a>
        </div>
      </div>
      <div class="column--60 align-self-end">
        <?php if ($number_of_phones === 1) { ?>
          <div class="container centered phones mt--40">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/iphone_mongabay_app.png" alt="Mongabay App">
          </div>
        <?php } else { ?>
        <div class="container in-row phones">
          <img class="left" src="<?php echo get_stylesheet_directory_uri(); ?>/img/iphone_mongabay_app_left.png" alt="Mongabay App">
          <img class="right" src="<?php echo get_stylesheet_directory_uri(); ?>/img/iphone_mongabay_app_right.png" alt="Mongabay App">
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
<?php }

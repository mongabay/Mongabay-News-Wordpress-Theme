<?php

/**
 * Template Name: Subscribe Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<div class="container section--headline in-column gap--40 ph--40 pv--80 align-left">
  <h1><?php _e('Select the newsletters you’re interested in and subscribe or follow us on social media.', 'mongabay'); ?></h1>
  <div class="container full-width in-row gap--20 space-between">
    <input type="email" id="subscribe-email" name="subscribe-email" placeholder="<?php _e('Type e-mail address', 'mongabay'); ?>" required>
    <a id="newsleter-subscribe" class="theme--button primary"><?php _e('Subscribe', 'mongabay'); ?><span class="icon icon-right"></span></a>
  </div>
  <label for="concent"><?php _e('I agree with Terms of Use.', 'mongabay'); ?>
    <input type="checkbox" id="concent" name="concent" checked>
  </label>
</div>
<div class="container">
  <div class="grid--3 gap--40 ph--40 pv--80">
    <div id="subscribe-all-banner" class="banner bg-theme-accent gap--20 ph--40 pv--40 full-height justify-center align-left" data-value="3c8a45e4b0">
      <div class="title">
        <h1 class="lh--tightest"><?php _e('Get all newsletters', 'mongabay'); ?></h1>
      </div>
      <a class="theme--button primary" id="subscribe-all"><?php _e('Select all newsletters'); ?></a>
    </div>
  </div>
</div>
<div class="container ph--40">
  <a class="theme--button primary" id="subscribe-options-more"><?php _e('Load more', 'mongabay'); ?></a>
</div>
<div class="container full-width ph--40 pv--40 in-column">
  <?php tools_slider(); ?>
</div>
</div>
<?php inspiration_banner(); ?>
<?php get_footer(); ?>
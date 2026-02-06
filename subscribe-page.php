<?php

/**
 * Template Name: Subscribe Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<div class="container in-column gap--40 ph--40">
  <div class="container section--headline in-column pv--80 align-left">
    <h1><?php _e('Select the newsletters you’re interested in and subscribe or follow us on social media.', 'mongabay'); ?></h1>
  </div>
  <div id="step-1">
    <div class="container full-width in-column gap--40">
      <div class="container in-row gap--20">
        <input type="text" id="subscribe-name" name="subscribe-name" placeholder="<?php _e('First name *', 'mongabay'); ?>" required>
        <input type="text" id="subscribe-last-name" name="subscribe-last-name" placeholder="<?php _e('Last name *', 'mongabay'); ?>" required>
      </div>
      <input type="text" id="subscribe-company" name="subscribe-company" placeholder="<?php _e('Company / Organization / Institution', 'mongabay'); ?>">
      <input type="email" id="subscribe-email" name="subscribe-email" placeholder="<?php _e('Type e-mail address *', 'mongabay'); ?>" required>
      <div class="container in-column">
        <h2><?php _e('Marketing permissions', 'mongabay'); ?></h2>
        <p><?php _e('Mongabay.com will use the information you provide on this form to be in touch with you and to provide updates and marketing. Please let us know all the ways you would like to hear from us:'); ?></p>
        <div class="container">
          <div class="container in-row gap--20">
            <label for="gdpr_1">
              <?php _e('Email newsletters', 'mongabay'); ?>
              <input type="checkbox" id="gdpr_1" name="gdpr[1]" checked>
            </label>
            <label for="gdpr_5">
              <?php _e('Direct mail', 'mongabay'); ?>
              <input type="checkbox" id="gdpr_5" name="gdpr[5]" checked>
            </label>
            <label for="gdpr_9">
              <?php _e('Customized online advertising', 'mongabay'); ?>
              <input type="checkbox" id="gdpr_9" name="gdpr[9]" checked>
            </label>
          </div>
        </div>
      </div>
      <div class="container pv--80 in-row align-right">
        <a href="#" class="theme--button primary next-step" data-current-step="1" data-next-step="2"><?php _e('Next step', 'mongabay'); ?> <span class="icon icon-right"></span></a>
      </div>
    </div>
  </div>
  <div id="step-2">
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
    <div class="container ph--40 pv--80 in-row space-between">
      <a href="#" class="theme--button secondary prev-step" data-current-step="2" data-prev-step="1"><span class="icon icon-left"></span> <?php _e('Previous step', 'mongabay'); ?></a>
      <a id="newsleter-subscribe" class="theme--button primary"><?php _e('Subscribe', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  </div>
</div>
<div class="container full-width pv--40 in-column">
  <?php tools_slider(); ?>
</div>
<?php inspiration_banner(); ?>
<?php get_footer(); ?>
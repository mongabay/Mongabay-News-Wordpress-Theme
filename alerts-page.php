<?php

/**
 * Template Name: Alerts Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>

<main id="alerts-page" class="alerts-page">
  <section class="alerts-content-pane" aria-label="<?php esc_attr_e('News alerts', 'mongabay'); ?>">
    <header class="alerts-content-header">
      <h1><?php esc_html_e('All News', 'mongabay'); ?></h1>
      <div class="alerts-filter-controls">
        <label>
          <span class="screen-reader-text"><?php esc_html_e('Filter by location', 'mongabay'); ?></span>
          <select id="alerts-location-filter">
            <option value="all"><?php esc_html_e('All locations', 'mongabay'); ?></option>
          </select>
        </label>
        <label>
          <span class="screen-reader-text"><?php esc_html_e('Filter by topic', 'mongabay'); ?></span>
          <select id="alerts-topic-filter">
            <option value="all"><?php esc_html_e('All topics', 'mongabay'); ?></option>
          </select>
        </label>
        <!-- <label>
          <span class="screen-reader-text"><?php esc_html_e('Filter by format', 'mongabay'); ?></span>
          <select id="alerts-format-filter">
            <option value="all"><?php esc_html_e('All formats', 'mongabay'); ?></option>
          </select>
        </label> -->
      </div>
    </header>
    <p class="alerts-results-status" id="alerts-results-status" role="status" aria-live="polite"></p>
    <div class="alerts-slider-wrapper">
      <button class="alerts-slider-nav alerts-slider-nav-prev" id="alerts-slider-prev" aria-label="<?php esc_attr_e('Previous articles', 'mongabay'); ?>" type="button">
        <span class="icon icon-left" aria-hidden="true"></span>
      </button>
      <div class="alerts-article-grid" id="alerts-article-grid"></div>
      <button class="alerts-slider-nav alerts-slider-nav-next" id="alerts-slider-next" aria-label="<?php esc_attr_e('Next articles', 'mongabay'); ?>" type="button">
        <span class="icon icon-right" aria-hidden="true"></span>
      </button>
    </div>
  </section>
  <div class="alerts-globe" id="alerts-globe" aria-label="<?php esc_attr_e('Map of news alerts', 'mongabay'); ?>">
    <p class="alerts-map-message" id="alerts-map-message" hidden></p>
  </div>
</main>

<dialog class="alerts-article-dialog" id="alerts-article-dialog" aria-labelledby="alerts-dialog-title">
  <button class="alerts-dialog-close" id="alerts-dialog-close" type="button" aria-label="<?php esc_attr_e('Close article', 'mongabay'); ?>">
    <span class="icon icon-cancel" aria-hidden="true"></span>
  </button>
  <article class="alerts-dialog-article">
    <div class="alerts-dialog-meta">
      <span id="alerts-dialog-byline"></span>
      <time id="alerts-dialog-date"></time>
    </div>
    <h2 id="alerts-dialog-title"></h2>
    <div class="alerts-dialog-image" id="alerts-dialog-image"></div>
    <div class="alerts-dialog-content" id="alerts-dialog-content"></div>
    <a class="theme--button primary" id="alerts-dialog-link" href="#"><?php esc_html_e('Read full article', 'mongabay'); ?><span class="icon icon-right" aria-hidden="true"></span></a>
  </article>
</dialog>

<?php get_footer(); ?>
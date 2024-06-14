<?php

/**
 * Template Name: Landing Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>

<div class="container content gap--40">
	<div class="column--20 latest-news">
		<div class="section-title gap--8">
			<h4><?php _e('Latest', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<?php articles_listing('post', 6, 5, false, 'medium', null, null, null); ?>
		<a class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
	</div>
	<div class="column--60 latest-featured gap--8">
		<div class="section-title gap--16">
			<h4><?php _e('Top stories', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<?php featured_articles_listing('post', 5, 0, 'medium', 2, true, null, null); ?>
	</div>
	<div class="column--20 gap--40 pv--40 latest-banners">
		<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'outlined ph--20 pv--20', ''); ?>
		<?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', ''); ?>
	</div>
</div>

<?php inspiration_banner(); ?>

<div class="container ph--40 pv--40 in-column">
	<?php series_articles_listing(true); ?>

	<div class="container full-width pv--40">
		<?php banner('', 'Free and open access to credible information', '', 'Learn more', 'accent full-width pv--56', 'extra-large'); ?>
	</div>

	<div class="section--highlight container full-width pv--40">
		<?php podcasts_banner(); ?>
	</div>

	<div class="container in-column pv--80 gap--40">
		<div class="container section--highlight">
			<h1>Explore engaging <span class="icon icon-play">videos</span> about pressing issues.</h1>
		</div>
		<?php featured_articles_listing('videos', 5, 0, 'medium', 4, false, null, null); ?>
	</div>

	<div class="container full-width pv--40">
		<?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
	</div>
</div>

<div class="full-width pv--40">
	<?php featured_articles_slider(4, 5); ?>
</div>

<div class="container ph--40 pv--40 in-column">
	<div class="section--highlight">
		<div class="container gap--20 in-column">
			<h1>Get quick glances with our brief <span class="icon icon-shorts">shorts</span></h1>
			<div class="grid--3 gap--40">
				<?php articles_listing_condensed('post', 6, 0, false, 'medium', null); ?>
			</div>
		</div>
	</div>

	<div class="container pv--40 full-width">
		<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
	</div>
</div>
<?php get_footer(); ?>
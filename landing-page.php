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
	<!-- Component Start -->
	<div class="column--20 latest-news">
		<div class="section-title gap--8">
			<h4><?php _e('Latest', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<?php articles_listing(6, 5, false, 'medium', null); ?>
		<a class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
		<!-- Component End -->
	</div>
	<div class="column--60 latest-featured gap--8">
		<!-- Component Start -->
		<div class="section-title gap--16">
			<h4><?php _e('Top stories', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<?php featured_articles_listing('post', 5, 0, 'medium', 2, true, null); ?>
		<!-- Component End -->
	</div>
	<div class="column--20 gap--40 pv--40 latest-banners">
		<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'outlined ph--20 pv--20'); ?>
		<?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width'); ?>
	</div>
</div>

<?php inspiration_banner(); ?>

<?php series_articles_listing(); ?>

<div class="container gap--32 pv--40">
	<div class="banner full-width accent pv--56">
		<div class="inner">
			<div class="title">
				<h1 class="extra-large"><?php _e('Free and open access to credible information.', 'mongabay'); ?></h1>
			</div>
			<a href="" class="theme--button primary"><?php _e('Donate', 'mongabay'); ?><span class="icon icon-right"></span></a>
		</div>
	</div>
</div>

<div class="section--highlight full-width ph--40 pv--40">
	<?php podcastsBanner(); ?>
</div>

<div class="container ph--40 pv--40">
	<?php banner('', 'Free and open access to credible information.', '', 'Learn more', 'accent ph--20 pv--56 full-width'); ?>
</div>

<div class="container ph--40 pv--40">
	<?php featured_articles_listing('videos', 5, 0, 'medium', 4, false, null); ?>
</div>

<div class="container ph--40 pv--40">
	<?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width'); ?>
</div>

<?php featured_articles_slider(4, 5); ?>

<div class="container ph--40 pv--40">
	<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width'); ?>
</div>

<div class="section--highlight">
	<div class="container ph--40 pv--40 gap--20 in-column">
		<h1>Get quick glances with our brief <span>shorts</span></h1>
		<div class="grid--3 gap--40">
			<?php articles_listing_condensed('post', 6, 0, false, 'medium', null); ?>
		</div>
	</div>
</div>
<!-- /container -->
<?php get_footer(); ?>
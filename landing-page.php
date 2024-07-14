<?php

/**
 * Template Name: Landing Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header();
?>

<div class="container content gap--40">
	<div class="column--20 latest-news">
		<div class="section-title gap--8">
			<h4><?php _e('Latest', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<div class="container in-column gap--16 pv--8">
			<?php articles_listing(array('post'), 6, 5, false, 'medium', null, null, null); ?>
			<a href="<?php echo home_url(); ?>/?s=&formats=<?php echo get_default_search_formats(get_default_formats()); ?>" class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
		</div>
	</div>
	<div class="column--60 latest-featured gap--8">
		<div class="section-title gap--16">
			<h4><?php _e('Top stories', 'mongabay'); ?></h4>
			<div class="divider"></div>
		</div>
		<?php featured_articles_listing(array('post', 'videos', 'podcasts', 'short-article', 'custom-story'), 5, 0, 'medium', 2, true, null, null); ?>
	</div>
	<div class="column--20 gap--40 pv--40 latest-banners" style="padding-top: 26px">
		<?php banner(get_subscribe_link(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'outlined ph--20 pv--20 gap--16 full-width', 'lh--tightest', 'full-width'); ?>
		<?php banner(get_donate_link(), 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--56 full-width gap--16', 'lh--tightest', 'full-width'); ?>
	</div>
</div>

<?php inspiration_banner(); ?>

<div class="container ph--40 pv--40 in-column">
	<?php series_articles_slider(true, 'specials'); ?>

	<div class="container full-width pv--40">
		<?php banner(home_url() . '/about', 'Free and open access to credible information', '', 'Learn more', 'accent full-width pv--56 ph--20 gap--32', 'extra-large', ''); ?>
	</div>

	<div class="section--highlight container full-width pv--40">
		<?php podcasts_banner(); ?>
	</div>

	<div class="container in-column pv--80 gap--40">
		<div class="container section--highlight">
			<h1><?php _e('Watch unique <span class="icon icon-play">videos</span> that cut through the noise', 'mongabay'); ?></h1>
		</div>
		<?php featured_articles_listing(array('videos'), 5, 0, 'medium', 4, false, null, null); ?>
	</div>

	<div class="container full-width pv--40">
		<?php banner(get_donate_link(), 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--56 gap--32 full-width', 'extra-large', ''); ?>
	</div>
</div>

<div class="full-width pv--40">
	<?php featured_articles_slider(array('post', 'custom-story'), 4, 5); ?>
</div>

<div class="container ph--40 pv--40 in-column">
	<div class="section--highlight">
		<div class="container gap--40 in-column">
			<h1><?php _e('Quickly stay updated with our news <span class="icon icon-shorts">shorts</span>', 'mongabay'); ?></h1>
			<div class="shorts grid--3 gap--40">
				<?php articles_listing_condensed('short-article', 6, 0, false, 'medium', null); ?>
			</div>
		</div>
	</div>

	<div class="container pv--40 full-width">
		<?php banner(get_subscribe_link(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 gap--32 full-width', 'extra-large', ''); ?>
	</div>
</div>
<?php get_footer(); ?>
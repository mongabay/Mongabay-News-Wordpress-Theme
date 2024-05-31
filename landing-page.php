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
		<?php featured_articles_listing('post', 5, 0, 'medium', null); ?>
		<!-- Component End -->
	</div>
	<div class="column--20 gap--40 ph--40 latest-banners">
		<!-- Component Start -->
		<div class="banner outlined gap--20 ph--20 pv--20">
			<div class="title">
				<h1><?php _e('Stay updated', 'mongabay'); ?></h1>
			</div>
			<div class="copy"><?php _e('Delivering news and inspiration from nature’s frontline.', 'mongabay'); ?></div>
			<a href="" class="theme--button full-width primary"><?php _e('Newsletter', 'mongabay'); ?><span class="icon icon-right"></span></a>
		</div>
		<div class="banner accent gap--20 ph--20 pv--20">
			<div class="title">
				<h1><?php _e('We are nonprofit', 'mongabay'); ?></h1>
			</div>
			<div class="copy">
				<?php _e('Help us tell stories of biodiversity loss, climate change and more.', 'mongabay'); ?>
			</div>
			<a href="" class="theme--button full-width primary"><?php _e('Donate', 'momgabay'); ?><span class="icon icon-right"></span></a>
		</div>
	</div>
</div>

<?php inspiration_banner(); ?>
<?php series_articles_listing(); ?>
<!-- /container -->
<?php get_footer(); ?>
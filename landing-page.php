<?php

/**
 * Template Name: Landing Page
 *
 * @package WordPress
 * @subpackage Mongabay_v2
 * @since Mongabay 1.0
 */
get_header(); ?>


<div class="container gap--40">
	<!-- Component Start -->
	<div class="column--20 latest-news">
		<div class="section-title gap--8">
			<h4>Latest</h4>
			<div class="divider"></div>
		</div>
		<?php articles_listing(6, 5, false, 'thumbnail', null); ?>
		<a class="theme--button outlined full-width">All news <span class="icon icon-right"></span></a>
		<!-- Component End -->
	</div>
	<div class="column--60 latest-featured gap--8">
		<!-- Component Start -->
		<div class="section-title gap--16">
			<h4>Top stories</h4>
			<div class="divider"></div>
		</div>
		<?php featured_articles_listing(5, 0, 'thumbnail', null); ?>
		<!-- Component End -->
	</div>
	<div class="column--20 gap--40 ph--40 latest-banners">
		<!-- Component Start -->
		<div class="banner outlined gap--20 ph--20 pv--20">
			<div class="title">
				<h1>Stay updated</h1>
			</div>
			<div class="copy">Delivering news and inspiration from nature’s frontline.</div>
			<a href="" class="theme--button full-width primary">Newsletter <span class="icon icon-right"></span></a>
		</div>
		<!-- Component End -->
		<!-- Component Start -->
		<div class="banner accent gap--20 ph--20 pv--20">
			<div class="title">
				<h1>We are nonprofit</h1>
			</div>
			<div class="copy">
				Help us tell stories of biodiversity loss, climate change and more.
			</div>
			<a href="" class="theme--button full-width primary">Donate <span class="icon icon-right"></span></a>
		</div>
		<!-- Component End -->
	</div>
</div>
<!-- /container -->
<?php get_footer(); ?>
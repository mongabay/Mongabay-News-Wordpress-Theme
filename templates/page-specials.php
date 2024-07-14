<?php

/**
 * The template for displaying single taxonomy serial
 *
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header(); ?>

<?php
$queried_object = get_queried_object();
$taxonomy = $queried_object->term_id;
$tax_name = $queried_object->name;
$tax_slug = $queried_object->slug;
$tax_description = $queried_object->description;
$formats = array('specials');
?>
<div class="container in-column gap--40">
	<div class="container section--highlight full-width in-column text-center ph--40 pv--40 gap--20">
		<h1 class="text-center"><?php echo $tax_name; ?></h1>
		<?php if (!empty($tax_description)) { ?>
			<p><?php echo $tax_description; ?></p>
		<?php } ?>
	</div>
	<div class="container in-row gap--40" style="margin-top: calc(-1 * var(--spacing-3))">
		<div class="column--80 latest-featured gap--8 pv--8">
			<?php featured_articles_listing($formats, 10, 0, 'medium', 3, false, 'serial', $tax_slug); ?>
		</div>
		<div class="column--20 latest-news">
			<?php articles_listing(array('post'), 10, 0, false, 'medium', null, null, 'serial'); ?>
			<a href="<?php echo home_url(); ?>/?s=" class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
		</div>
	</div>
</div>

<div class="container in-column pv--40 gap--40">
	<div class="container in-row full-width section--headline" style="align-items: center; justify-content: space-between;">
		<h1><?php _e('Latest Specials', 'mongabay'); ?></h1>
	</div>
	<?php articles_listing_in_columns($formats, 6, 0, 'medium', 'serial', true); ?>

	<?php banner(get_donate_link(), 'We’re a nonprofit', 'Help us tell impactful stories of biodiversity loss, climate change, and more', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>
<?php featured_articles_slider($formats, 4, 5); ?>

<div class="container pv--40">
	<?php banner(get_subscribe_link(), 'Subscribe', 'Stay informed with news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<div class="section--highlight">
	<div class="container pv--80 gap--20 in-column">
		<h1><?php _e('Quickly stay updated with our news <span>shorts</span>', 'mongabay'); ?></h1>
		<div class="grid--3 gap--40">
			<?php articles_listing_condensed('short-article', 6, 0, false, 'medium', null); ?>
		</div>
	</div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
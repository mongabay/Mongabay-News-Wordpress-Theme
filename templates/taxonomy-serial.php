<?php

/**
 * The template for displaying taxonomy topics
 *
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header(); ?>

<?php
$taxonomy = get_queried_object()->term_id;
$tax_name = get_queried_object()->name;
$tax_slug = get_queried_object()->slug;
$tax_description = get_queried_object()->description;
?>
<div class="container in-column gap--40">
	<div class="container section--highlight full-width in-column text-center ph--40 pv--40 gap--20">
		<h1 class="text-center"><?php echo $tax_name; ?></h1>
		<?php if (!empty($tax_description)) { ?>
			<p><?php echo $tax_description; ?></p>
		<?php } ?>
	</div>
	<div class="container in-row gap--40">
		<div class="column--80 latest-featured gap--8">
			<?php featured_articles_listing('post', 10, 0, 'medium', 3, false, 'serial', $tax_slug); ?>
		</div>
		<div class="column--20 latest-news">
			<div class="section-title gap--8">
				<h4><?php _e('Latest', 'mongabay'); ?></h4>
				<div class="divider"></div>
			</div>
			<?php articles_listing('post', 10, 0, false, 'medium', null, null, 'serial'); ?>
			<a href="<?php home_url(); ?>/?s=" class="theme--button outlined full-width"><?php _e('All news', 'mongabay'); ?><span class="icon icon-right"></span></a>
		</div>
	</div>
</div>

<div class="container in-column ph--40 pv--40">
	<?php banner('', 'We are nonprofit', 'Help us tell stories of biodiversity loss, climate change and more.', 'Donate', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
	<?php topics_section('Explore articles by topic such as', array('climate', 'oceans')); ?>
</div>
<?php featured_articles_slider(4, 5); ?>

<div class="container ph--40 pv--40">
	<?php banner('', 'Stay updated', 'Delivering news and inspiration from nature’s frontline.', 'Newsletter', 'accent ph--20 pv--56 full-width', 'extra-large'); ?>
</div>

<div class="section--highlight">
	<div class="container ph--40 pv--80 gap--20 in-column">
		<h1>Get quick glances with our brief <span>shorts</span></h1>
		<div class="grid--3 gap--40">
			<?php articles_listing_condensed('post', 6, 0, false, 'medium', null); ?>
		</div>
	</div>
</div>

<?php inspiration_banner(); ?>

<?php get_footer(); ?>
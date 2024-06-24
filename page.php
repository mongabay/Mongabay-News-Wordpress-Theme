<?php get_header(); ?>
<main role="main">
	<div class="container in-column ph--40 pv--40">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<!-- article -->
				<?php $post_id = get_the_ID(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php mongabay_sanitized_content($post_id); ?>
				</article>
				<!-- /article -->
			<?php endwhile; ?>
		<?php else : ?>
			<!-- article -->
			<article>
				<h1><?php _e('Sorry, nothing to display.', 'mongabay'); ?></h1>
			</article>
			<!-- /article -->
		<?php endif; ?>
	</div>
	<?php inspiration_banner(); ?>
	<!-- /main -->
</main>

<!-- /container -->
<?php get_footer(); ?>
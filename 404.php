<?php get_header(); ?>
<main role="main">
	<div class="container ph--40 pv--40">
		<article id="post-404">
			<h1><?php _e('Page not found', 'mongabay'); ?></h1>
			<h2>
				<a href="<?php echo home_url(); ?>"><?php _e('Return home?', 'mongabay'); ?></a>
			</h2>
		</article>
	</div>
</main>
<?php get_footer(); ?>
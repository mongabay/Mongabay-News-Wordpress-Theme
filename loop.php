<article id="post-<?php the_ID(); ?>" class="post-news">
	<?php if (has_post_thumbnail()) : ?>
		<div class="hidden-md-up">
			<?php echo get_the_post_thumbnail(get_the_ID(), 'medium') ?>
		</div>
	<?php endif; ?>
	<h2 class="post-title-news">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<div class="entry-meta-news">
		<?php
		_e('by ', 'mongabay');
		var_dump(get_the_term_list(get_the_ID(), 'byline', '', ', ', ''));
		echo get_the_term_list(get_the_ID(), 'byline', '', ', ', '');
		echo ' ';
		the_time('j F Y');
		?>
	</div>
	<div class="excerpt-news">
		<?php
		mongabay_excerpt();
		?>
	</div>
	<?php if (has_post_thumbnail()) : ?>
		<div class="thumbnail-news hidden-xs-down">
			<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail') ?>
		</div>
	<?php endif; ?>
</article>
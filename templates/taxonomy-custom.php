<?php

/**
 * The template for displaying taxonomy topics
 *
 */

defined('ABSPATH') || exit; // Exit if accessed directly

get_header(); ?>

<div class="container in-column ph--40 pv--40">
	<?php if (have_posts()) { ?>
		<?php
		$title = get_query_var('term');
		$term = get_query_var('list');
		$first = get_query_var('nc1');

		global $wp_query;
		$taxonomies = $wp_query->tax_query;
		$topics = array();

		foreach ($taxonomies->queries as $tax_query) {
			if (isset($tax_query['taxonomy'])) {
				$topics[] = ucfirst(str_replace('-', ' ', $tax_query['terms'][0]));
			}
		}

		$line_end = ' News';
		$total = $wp_query->found_posts;
		?>
		<div class="container full-width section--highlight">
			<h1><?php echo implode(' and ', $topics); ?> <?php _e($line_end, 'mongabay'); ?></h1>
		</div>

		<div id="results">
			<div id="results-header">
				<div id="results-header-left">
					<a href="<?php get_home_url() ?>/?feed=custom&s=&post_type=posts&topic=<?php echo $title; ?>" target="_blank" id="results-rss" class="theme--button primary simple">RSS</a>
					<div id="results-total"><?php echo $total; ?> <?php _e($total > 1 ? 'stories' : 'story', 'mongabay'); ?></div>
				</div>
				<div id="results-view-toggles">
					<button id="list-view">L</button>
					<button id="grid-view" class="active">G</button>
				</div>
			</div>
			<div id="post-results" class="grid-view">
				<?php
				// Start the Loop
				while (have_posts()) :
					the_post();
				?>
					<div class="article--container pv--8">
						<a href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) { ?>
								<div class="featured-image">
									<?php echo get_icon(get_the_ID()); ?>
									<?php the_post_thumbnail('medium') ?>
								</div>
							<?php }; ?>
							<div class="title headline">
								<h3><?php the_title(); ?></h3>
							</div>
							<div class="meta pv--8">
								<span class="byline"><?php echo getPostBylines(get_the_ID()); ?></span>
								<span class="date"><?php the_time('j F Y'); ?></span>
							</div>
						</a>
					</div>
				<?php endwhile; ?>
			</div>
			<script>
				const gridViewButton = document.getElementById("grid-view");
				const listViewButton = document.getElementById("list-view");
				const resultsList = document.getElementById("post-results");

				gridViewButton.addEventListener("click", () => {
					resultsList.classList.remove("list-view");
					resultsList.classList.add("grid-view");
					gridViewButton.classList.add("active");
					listViewButton.classList.remove("active");
				});

				listViewButton.addEventListener("click", () => {
					resultsList.classList.remove("grid-view");
					resultsList.classList.add("list-view");
					listViewButton.classList.add("active");
					gridViewButton.classList.remove("active");
				});
			</script>
		</div>
		<div class="counter">
			<?php mongabay_pagination(); ?>
		</div>
	<?php } else {
		_e('No results found', 'mongabay');
	}
	?>
</div>

<?php get_footer(); ?>